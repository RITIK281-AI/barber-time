<h2>New Partner Request Received</h2>

<p>Hello Admin,</p>

<p>A new barber shop has requested to become a BarberTime partner:</p>

<table style="border-collapse: collapse; width: 100%; max-width: 500px;">
    <tr>
        <td style="padding: 8px; border: 1px solid #ddd; font-weight: bold;">Shop Name</td>
        <td style="padding: 8px; border: 1px solid #ddd;">{{ $barberShop->name }}</td>
    </tr>
    <tr>
        <td style="padding: 8px; border: 1px solid #ddd; font-weight: bold;">Owner</td>
        <td style="padding: 8px; border: 1px solid #ddd;">{{ $barberShop->owner_name }}</td>
    </tr>
    <tr>
        <td style="padding: 8px; border: 1px solid #ddd; font-weight: bold;">Email</td>
        <td style="padding: 8px; border: 1px solid #ddd;">{{ $barberShop->email }}</td>
    </tr>
    <tr>
        <td style="padding: 8px; border: 1px solid #ddd; font-weight: bold;">Phone</td>
        <td style="padding: 8px; border: 1px solid #ddd;">{{ $barberShop->phone }}</td>
    </tr>
    <tr>
        <td style="padding: 8px; border: 1px solid #ddd; font-weight: bold;">Location</td>
        <td style="padding: 8px; border: 1px solid #ddd;">{{ $barberShop->address }}, {{ $barberShop->district }}</td>
    </tr>
    <tr>
        <td style="padding: 8px; border: 1px solid #ddd; font-weight: bold;">Barbers</td>
        <td style="padding: 8px; border: 1px solid #ddd;">{{ $barberShop->number_of_barbers }}</td>
    </tr>
    <tr>
        <td style="padding: 8px; border: 1px solid #ddd; font-weight: bold;">Services</td>
        <td style="padding: 8px; border: 1px solid #ddd;">{{ $barberShop->services_offered }}</td>
    </tr>
</table>

<p style="margin-top: 20px;">Please log in to the admin panel to review this request.</p>

<p>— BarberTime System</p>
