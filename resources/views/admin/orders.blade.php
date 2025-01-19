{{-- resources/views/admin/orders.blade.php --}}
<x-layout bodyClass="g-sidenav-show bg-gray-200">
    <x-navbars.sidebar activePage="orders"></x-navbars.sidebar>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
        <x-navbars.navs.auth titlePage="Order Management"></x-navbars.navs.auth>
        <div class="container-fluid py-4">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <h6>Orders Overview</h6>
                </div>
                <div class="card-body">
                    <table class="table align-items-center">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Field</th>
                                <th>Slot Start</th>
                                <th>Slot End</th>
                                <th>Price</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                @foreach ($order->orderItems as $item)
                                    <tr>
                                        <td>{{ $order->user->name }}</td>
                                        <td>{{ $item->field_name }}</td>
                                        <td>{{ $item->slot_start }}</td>
                                        <td>{{ $item->slot_end }}</td>
                                        <td>{{ $item->price }}</td>
                                        <td>{{ $order->payment_status }}</td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</x-layout>
