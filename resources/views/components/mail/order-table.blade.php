<div style="margin: 20px auto;">
    <h3 style="color: #555;font-size: 1.5em;">Detalhes do pedido</h3>
    <table style="width: 100%;border-collapse: collapse;">
        <tr>
            <td style="background-color: #ddd;padding: 5px;font-weight: bold;color: #555;">Pedido</td>
            <td style="background-color: #ddd;padding: 5px;">{{ $orderId }}</td>
        </tr>
        <tr>
            <td style="background-color: #eee;padding: 5px;font-weight: bold;color: #555;">Destino</td>
            <td style="background-color: #eee;padding: 5px;">{{ $destination }}</td>
        </tr>
        <tr>
            <td style="background-color: #ddd;padding: 5px;font-weight: bold;color: #555;">Data de ida</td>
            <td style="background-color: #ddd;padding: 5px;">{{ $departureDate }}</td>
        </tr>
        <tr>
            <td style="background-color: #eee;padding: 5px;font-weight: bold;color: #555;">Data de volta</td>
            <td style="background-color: #eee;padding: 5px;">{{ $arrivalDate }}</td>
        </tr>
    </table>
</div>
