@extends('layout')

@section('content')

<div class="top">
    <h2>Customers</h2>
    <button class="btn" onclick="openModal()">+ Add Data</button>
</div>

<div class="card">
    <input class="search" type="text" placeholder="Search customer..." oninput="searchTable(this.value, 'customerTable')">

    <table id="customerTable">
        <thead>
            <tr>
                <th>Customer ID</th>
                <th>Customer Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $customer)
            <tr>
                <td>{{ $customer->customer_id }}</td>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->email }}</td>
                <td>{{ $customer->phone }}</td>
                <td>{{ $customer->address }}</td>
                <td>
                    @if($customer->status)
                        <span class="badge badge-active">Active</span>
                    @else
                        <span class="badge badge-inactive">Inactive</span>
                    @endif
                </td>
                <td>
                    <div class="action-wrap">
                        <button class="action-btn" onclick="toggleDropdown(this)">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                                <circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/>
                            </svg>
                        </button>
                        <div class="dropdown-menu">
                            {{-- Active / Deactivate toggle --}}
                            @if($customer->status)
                            <form method="POST" action="/customers-setstatus/{{ $customer->id }}">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="0">
                                <button type="submit" class="dropdown-item">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                                    Deactivate
                                </button>
                            </form>
                            @else
                            <form method="POST" action="/customers-setstatus/{{ $customer->id }}">
                                @csrf @method('PUT')
                                <input type="hidden" name="status" value="1">
                                <button type="submit" class="dropdown-item">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                                    Active
                                </button>
                            </form>
                            @endif

                            <button class="dropdown-item"
                                data-id="{{ $customer->id }}"
                                data-customer_id="{{ $customer->customer_id }}"
                                data-name="{{ $customer->name }}"
                                data-email="{{ $customer->email }}"
                                data-phone="{{ $customer->phone }}"
                                data-address="{{ $customer->address }}"
                                data-status="{{ $customer->status }}"
                                onclick="openEditModal(this)">
                                <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                Edit
                            </button>

                            <form method="POST" action="/customers-delete/{{ $customer->id }}" onsubmit="return confirm('Yakin hapus customer ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="dropdown-item danger">
                                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

{{-- Modal Add Customer --}}
<div class="modal" id="customerModal">
    <div class="modal-box">
        <h3>Add Customer</h3>
        <form method="POST" action="/customers-store">
            @csrf
            <div class="form-group">
                <label>Customer ID</label>
                <input type="text" name="customer_id" placeholder="Enter Customer ID" required>
            </div>
            <div class="form-group">
                <label>Customer Name</label>
                <input type="text" name="name" placeholder="Enter Customer Name" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" placeholder="Enter Email">
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" placeholder="Enter Phone Number">
            </div>
            <div class="form-group">
                <label>Address</label>
                <textarea name="address" placeholder="Enter Address"></textarea>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="modal-actions">
                <button class="btn-cancel" type="button" onclick="closeModal()">Cancel</button>
                <button class="btn" type="submit">Save</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit Customer --}}
<div class="modal" id="editModal">
    <div class="modal-box">
        <h3>Edit Customer</h3>
        <form method="POST" id="editForm" action="">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Customer ID</label>
                <input type="text" name="customer_id" id="edit_customer_id" placeholder="Customer ID" required>
            </div>
            <div class="form-group">
                <label>Customer Name</label>
                <input type="text" name="name" id="edit_name" placeholder="Customer Name" required>
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" id="edit_email" placeholder="Email">
            </div>
            <div class="form-group">
                <label>Phone</label>
                <input type="text" name="phone" id="edit_phone" placeholder="Phone">
            </div>
            <div class="form-group">
                <label>Address</label>
                <textarea name="address" id="edit_address" placeholder="Address"></textarea>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" id="edit_status">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="modal-actions">
                <button class="btn-cancel" type="button" onclick="closeEditModal()">Cancel</button>
                <button class="btn" type="submit">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('customerModal').style.display = 'flex';
}
function closeModal() {
    document.getElementById('customerModal').style.display = 'none';
}
function openEditModal(btn) {
    const d = btn.dataset;
    document.getElementById('editForm').action = '/customers-update/' + d.id;
    document.getElementById('edit_customer_id').value = d.customer_id;
    document.getElementById('edit_name').value = d.name;
    document.getElementById('edit_email').value = d.email;
    document.getElementById('edit_phone').value = d.phone;
    document.getElementById('edit_address').value = d.address;
    document.getElementById('edit_status').value = d.status;
    document.getElementById('editModal').style.display = 'flex';
    // tutup dropdown dulu
    document.querySelectorAll('.dropdown-menu.show').forEach(d => d.classList.remove('show'));
}
function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
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