<x-mail.container>
    <x-mail.header title="Pedido aprovado!"></x-mail.header>
    <div style="padding: 0 10px;">
        <h2 style="color: #555;font-size: 2em;">
            Olá, {{ $user }}!
        </h2>

        <p>
            Temos ótimas notícias!
        </p>

        <p>O seu pedido foi aprovado!</p>

        <x-mail.order-table
            :order-id="$orderId"
            :destination="$destination"
            :departure-date="$departureDate"
            :arrival-date="$arrivalDate"
        ></x-mail.order-table>
    </div>
    <x-mail.footer></x-mail.footer>
</x-mail.container>
