@extends('layout')

@section('content')

<div class="top">
    <h2>Subscriptions</h2>
    <button class="btn" onclick="document.getElementById('subscriptionModal').style.display='flex'">+ Add Data</button>
</div>

<div class="card">
    <input class="search" type="text" placeholder="Search subscription..." oninput="searchTable(this.value, 'subTable')">

    <table id="subTable">
        <thead>
            <tr>
                <th>Customer Name</th>
                <th>Services</th>
                <th>Services Period</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($subscriptions as $subscription)
            <tr>
                <td>{{ $subscription->customer->name }}</td>
                <td>{{ $subscription->service->name }}</td>
                <td>
                    {{ \Carbon\Carbon::parse($subscription->start_date)->format('d M Y') }}
                    –
                    {{ \Carbon\Carbon::parse($subscription->end_date)->format('d M Y') }}
                </td>
                <td>
                    @php
                        $statusMap = [
                            'active'    => 'badge-active',
                            'trial'     => 'badge-trial',
                            'isolir'    => 'badge-isolir',
                            'dismantle' => 'badge-dismantle',
                            'inactive'  => 'badge-inactive',
                        ];
                        $badgeClass = $statusMap[strtolower($subscription->status)] ?? 'badge-inactive';
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ ucfirst($subscription->status) }}</span>
                </td>
               <td>
    @php
        $currentStatus = strtolower($subscription->status);

        $statuses = [
            'active'    => ['✓', 'Active'],
            'inactive'  => ['🚫', 'Deactivate'],
            'trial'     => ['⌛', 'Trial'],
            'isolir'    => ['⛔', 'Isolir'],
            'dismantle' => ['●', 'Dismantle'],
        ];
    @endphp

    @if($currentStatus === 'dismantle')
        <span style="color:#6b7280; font-size:13px;">Locked</span>
    @else
        <div class="action-wrap">
            <button class="action-btn" onclick="toggleDropdown(this)" type="button">⋮</button>

            <div class="dropdown-menu">
                @foreach($statuses as $value => $item)
                    @if($currentStatus !== $value)
                        <form method="POST" action="/subscriptions-setstatus/{{ $subscription->id }}">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="status" value="{{ $value }}">

                            <button type="submit" class="dropdown-item">
                                <span style="width:18px;display:inline-block">{{ $item[0] }}</span>
                                {{ $item[1] }}
                            </button>
                        </form>
                    @endif
                @endforeach

                <button
                    class="dropdown-item"
                    type="button"
                    data-id="{{ $subscription->id }}"
                    data-customer_id="{{ $subscription->customer_id }}"
                    data-service_id="{{ $subscription->service_id }}"
                    data-start="{{ $subscription->start_date }}"
                    data-end="{{ $subscription->end_date }}"
                    data-status="{{ $subscription->status }}"
                    onclick="openEditSubscription(this)">

                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>

                    Edit
                </button>
            </div>
        </div>
    @endif
</td>

</tr>

@endforeach
</tbody>
</table>
</div>

{{-- Modal Add Subscription --}}
<div class="modal" id="subscriptionModal">
    <div class="modal-box">
        <h3>Add Subscription</h3>
        <form method="POST" action="/subscriptions-store">
            @csrf
            <div class="form-group">
                <label>Customer</label>
                <select name="customer_id" required>
                    <option value="" disabled selected>Select Customer</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Service</label>
                <select name="service_id" required>
                    <option value="" disabled selected>Select Service</option>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}">{{ $service->name }} — Rp{{ number_format($service->price, 0, ',', '.') }}</option>
                    @endforeach
                </select>
            </div>
            <div class="date-row">
                <div class="form-group">
                    <label>Start Date</label>
                    <input type="date" name="start_date" required>
                </div>
                <div class="form-group">
                    <label>End Date</label>
                    <input type="date" name="end_date" required>
                </div>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="active">Active</option>
                    <option value="trial">Trial</option>
                    <option value="isolir">Isolir</option>
                    <option value="dismantle">Dismantle</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="modal-actions">
                <button class="btn-cancel" type="button" onclick="document.getElementById('subscriptionModal').style.display='none'">Cancel</button>
                <button class="btn" type="submit">Save</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit Subscription --}}
<div class="modal" id="editSubModal">
    <div class="modal-box">
        <h3>Edit Subscription</h3>
        <form method="POST" id="editSubForm" action="">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Customer</label>
                <select name="customer_id" id="esub_customer" required>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Service</label>
                <select name="service_id" id="esub_service" required>
                    @foreach($services as $service)
                        <option value="{{ $service->id }}">{{ $service->name }} — Rp{{ number_format($service->price, 0, ',', '.') }}</option>
                    @endforeach
                </select>
            </div>
            <div class="date-row">
                <div class="form-group">
                    <label>Start Date</label>
                    <input type="date" name="start_date" id="esub_start" required>
                </div>
                <div class="form-group">
                    <label>End Date</label>
                    <input type="date" name="end_date" id="esub_end" required>
                </div>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" id="esub_status">
                    <option value="active">Active</option>
                    <option value="trial">Trial</option>
                    <option value="isolir">Isolir</option>
                    <option value="dismantle">Dismantle</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="modal-actions">
                <button class="btn-cancel" type="button" onclick="document.getElementById('editSubModal').style.display='none'">Cancel</button>
                <button class="btn" type="submit">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditSubscription(btn) {
    const d = btn.dataset;
    document.getElementById('editSubForm').action = '/subscriptions-update/' + d.id;
    document.getElementById('esub_customer').value = d.customer_id;
    document.getElementById('esub_service').value = d.service_id;
    document.getElementById('esub_start').value = d.start;
    document.getElementById('esub_end').value = d.end;
    document.getElementById('esub_status').value = d.status;
    document.getElementById('editSubModal').style.display = 'flex';
}
function searchTable(query, tableId) {
    const rows = document.querySelectorAll('#' + tableId + ' tbody tr');
    rows.forEach(row => {
        row.style.display = row.textContent.toLowerCase().includes(query.toLowerCase()) ? '' : 'none';
    });
}
document.querySelectorAll('.modal').forEach(modal => {
    modal.addEventListener('click', function(e) {
        if (e.target === this) this.style.display = 'none';
    });
});


</script>

@endsection

