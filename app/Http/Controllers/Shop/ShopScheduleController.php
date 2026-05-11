<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ShopClosedDay;
use App\Models\ShopHolidayDate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\Booking\BookingCancelledMail;

class ShopScheduleController extends Controller
{
    // get the authenticated user's shop or abort
    private function shop()
    {
        $shop = Auth::user()->barberShop;

        if (!$shop) {
            abort(403, 'No barber shop associated with this account.');
        }

        return $shop;
    }

    // show the full schedule management page (closed days + holidays)
    public function index()
    {
        $shop = $this->shop();

        // get current weekly closed day numbers as a plain array e.g. [0, 6]
        $closedDays = ShopClosedDay::where('shop_id', $shop->id)
            ->pluck('day_of_week')
            ->toArray();

        // get all holiday dates for this shop ordered by date
        $holidays = ShopHolidayDate::where('shop_id', $shop->id)
            ->orderBy('date', 'asc')
            ->get();

        $dayNames = ShopClosedDay::DAY_NAMES;

        return view('barbershop.schedule.index', compact('closedDays', 'dayNames', 'holidays'));
    }

    // save selected weekly closed days, replacing whatever was saved before
    public function updateClosedDays(Request $request)
    {
        $request->validate([
            'closed_days'   => 'nullable|array',
            'closed_days.*' => 'integer|between:0,6',
        ]);

        $shop = $this->shop();

        // remove all existing closed days for this shop
        ShopClosedDay::where('shop_id', $shop->id)->delete();

        // save each selected day
        foreach ($request->input('closed_days', []) as $day) {
            ShopClosedDay::create([
                'shop_id'     => $shop->id,
                'day_of_week' => (int) $day,
            ]);
        }

        return redirect()
            ->route('shop.schedule.index')
            ->with('success', 'Weekly closed days updated successfully.');
    }

    // show confirmation page before bulk cancelling a holiday date
    public function confirmHoliday(Request $request)
    {
        $request->validate([
            'date'   => 'required|date|after_or_equal:today',
            'reason' => 'nullable|string|max:255',
        ]);

        $shop = $this->shop();

        // count how many active bookings will be affected on this date
        $affectedCount = Booking::where('barber_shop_id', $shop->id)
            ->whereDate('booking_date', $request->date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        return view('barbershop.schedule.confirm', [
            'date'          => $request->date,
            'reason'        => $request->reason,
            'affectedCount' => $affectedCount,
        ]);
    }

    // store the holiday and bulk cancel all affected bookings
    public function storeHoliday(Request $request)
    {
        $request->validate([
            'date'   => 'required|date|after_or_equal:today',
            'reason' => 'nullable|string|max:255',
        ]);

        $shop = $this->shop();

        // check if this date is already marked as a holiday
        $alreadyExists = ShopHolidayDate::where('shop_id', $shop->id)
            ->where('date', $request->date)
            ->exists();

        if ($alreadyExists) {
            return redirect()
                ->route('shop.schedule.index')
                ->with('error', 'This date is already marked as a holiday.');
        }

        // save the holiday date
        ShopHolidayDate::create([
            'shop_id' => $shop->id,
            'date'    => $request->date,
            'reason'  => $request->reason,
        ]);

        // get all pending and confirmed bookings on this date
        $affectedBookings = Booking::with(['user', 'barberShop'])
            ->where('barber_shop_id', $shop->id)
            ->whereDate('booking_date', $request->date)
            ->whereIn('status', ['pending', 'confirmed'])
            ->get();

        // cancel each booking and send cancellation email to customer
        foreach ($affectedBookings as $booking) {
            $booking->update(['status' => 'cancelled']);

            if ($booking->user && $booking->user->email) {
                Mail::to($booking->user->email)->send(
                    new BookingCancelledMail($booking, 'barber_shop', $request->reason)
                );
            }
        }

        return redirect()
            ->route('shop.schedule.index')
            ->with('success', "Holiday added and {$affectedBookings->count()} booking(s) cancelled successfully.");
    }

    // delete a holiday date
    public function destroyHoliday($id)
    {
        $shop = $this->shop();

        $holiday = ShopHolidayDate::where('shop_id', $shop->id)->findOrFail($id);
        $holiday->delete();

        return redirect()
            ->route('shop.schedule.index')
            ->with('success', 'Holiday date removed successfully.');
    }
}
