@extends('layout')

@section('content')

<div class="top">
    <h2>Services</h2>
    <button class="btn" onclick="document.getElementById('serviceModal').style.display='flex'">+ Add Data</button>
</div>

<div class="card">
    <input class="search" type="text" placeholder="Search service..." oninput="searchTable(this.value, 'serviceTable')">

    <table id="serviceTable">
        <thead>
            <tr>
                <th>Service Name</th>
                <th>Price</th>
                <th>Description</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($services as $service)
            <tr>
                <td>{{ $service->name }}</td>
                <td>Rp{{ number_format($service->price, 0, ',', '.') }}</td>
                <td>{{ $service->description ?? '-' }}</td>
                <td>
                    @if($service->status)
                        <span class="badge badge-active">Active</span>
                    @else
                        <span class="badge badge-inactive">Inactive</span>
                    @endif
                </td>
                <td>
    <div class="action-wrap">
        <button class="action-btn" onclick="toggleDropdown(this)" type="button">⋮</button>

        <div class="dropdown-menu">

@if($service->status)

<form method="POST" action="/services-setstatus/{{ $service->id }}">
@csrf
@method('PUT')

<input type="hidden" name="status" value="0">

<button type="submit" class="dropdown-item">

<svg width="15" height="15"
viewBox="0 0 24 24"
fill="none"
stroke="currentColor"
stroke-width="2">

<circle cx="12" cy="12" r="10"/>
<line x1="5" y1="5" x2="19" y2="19"/>

</svg>

Deactivate

</button>

</form>

@else

<form method="POST" action="/services-setstatus/{{ $service->id }}">
@csrf
@method('PUT')

<input type="hidden" name="status" value="1">

<button type="submit" class="dropdown-item">

<svg width="15" height="15"
viewBox="0 0 24 24"
fill="none"
stroke="currentColor"
stroke-width="2">

<polyline points="20 6 9 17 4 12"/>

</svg>

Active

</button>

</form>

@endif


<button
class="dropdown-item"
type="button"
data-id="{{ $service->id }}"
data-name="{{ $service->name }}"
data-price="{{ $service->price }}"
data-description="{{ $service->description }}"
data-status="{{ $service->status }}"
onclick="openEditService(this)">

<svg width="15"
height="15"
viewBox="0 0 24 24"
fill="none"
stroke="currentColor"
stroke-width="2">

<path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
<path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>

</svg>

Edit

</button>


<form
method="POST"
action="/services-delete/{{ $service->id }}"
onsubmit="return confirm('Delete service?')">

@csrf
@method('DELETE')

<button
type="submit"
class="dropdown-item danger">

<svg width="15"
height="15"
viewBox="0 0 24 24"
fill="none"
stroke="currentColor"
stroke-width="2">

<polyline points="3 6 5 6 21 6"/>
<path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
<path d="M10 11v6"/>
<path d="M14 11v6"/>

</svg>

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

{{-- Modal Add Service --}}
<div class="modal" id="serviceModal">
    <div class="modal-box">
        <h3>Add Services</h3>
        <form method="POST" action="/services-store">
            @csrf
            <div class="form-group">
                <label>Service Name</label>
                <input name="name" placeholder="Enter service name" required>
            </div>
            <div class="form-group">
                <label>Price</label>
                <input name="price" type="number" placeholder="Enter price" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" placeholder="Enter description"></textarea>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="modal-actions">
                <button class="btn-cancel" type="button" onclick="document.getElementById('serviceModal').style.display='none'">Cancel</button>
                <button class="btn" type="submit">Save</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit Service --}}
<div class="modal" id="editServiceModal">
    <div class="modal-box">
        <h3>Edit Service</h3>
        <form method="POST" id="editServiceForm" action="">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Service Name</label>
                <input name="name" id="es_name" placeholder="Service name" required>
            </div>
            <div class="form-group">
                <label>Price</label>
                <input name="price" id="es_price" type="number" placeholder="Price" required>
            </div>
            <div class="form-group">
                <label>Description</label>
                <textarea name="description" id="es_description" placeholder="Description"></textarea>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" id="es_status">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="modal-actions">
                <button class="btn-cancel" type="button" onclick="document.getElementById('editServiceModal').style.display='none'">Cancel</button>
                <button class="btn" type="submit">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditService(btn) {
    const d = btn.dataset;
    document.getElementById('editServiceForm').action = '/services-update/' + d.id;
    document.getElementById('es_name').value = d.name;
    document.getElementById('es_price').value = d.price;
    document.getElementById('es_description').value = d.description;
    document.getElementById('es_status').value = d.status;
    document.getElementById('editServiceModal').style.display = 'flex';
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