<h2>Products</h2>

{{-- Success Message --}}
@if(session('success'))
    <p style="color:green">{{ session('success') }}</p>
@endif

{{-- Search --}}
<form method="GET">
    <input type="text" name="search" placeholder="Search name or SKU" value="{{ request('search') }}">
    <button type="submit">Search</button>
</form>

<br>

<a href="{{ route('products.create') }}">Add Product</a>

<table border="1" cellpadding="8">
    <tr>
        <th>Name</th>
        <th>SKU</th>
        <th>Price</th>
        <th>Stock</th>
        <th>Status</th>
        <th>Action</th>
    </tr>

@foreach($products as $product)
<tr>
    <td>{{ $product->name }}</td>
    <td>{{ $product->sku }}</td>
    <td>{{ $product->price }}</td>
    <td>{{ $product->stock }}</td>
    <td>{{ $product->is_active ? 'Active' : 'Inactive' }}</td>
    <td>
        <a href="{{ route('products.edit', $product->id) }}">Edit</a>

        {{-- Toggle --}}
        <form action="{{ route('products.toggle', $product->id) }}" method="POST" style="display:inline">
            @csrf
            @method('PATCH')
            <button type="submit">
                {{ $product->is_active ? 'Deactivate' : 'Activate' }}
            </button>
        </form>

        {{-- Delete --}}
        <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline">
            @csrf
            @method('DELETE')
            <button type="submit">Delete</button>
        </form>
    </td>
</tr>
@endforeach
</table>

<br>

{{ $products->links() }}
