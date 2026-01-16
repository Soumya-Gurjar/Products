<h2>Edit Product</h2>

@if ($errors->any())
    <ul style="color:red">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form method="POST" action="{{ route('products.update', $product->id) }}">
    @csrf
    @method('PUT')

    Name: <input type="text" name="name" value="{{ $product->name }}"><br><br>
    SKU: <input type="text" name="sku" value="{{ $product->sku }}"><br><br>
    Price: <input type="number" step="0.01" name="price" value="{{ $product->price }}"><br><br>
    Stock: <input type="number" name="stock" value="{{ $product->stock }}"><br><br>

    Active:
    <select name="is_active">
        <option value="1" {{ $product->is_active ? 'selected' : '' }}>Yes</option>
        <option value="0" {{ !$product->is_active ? 'selected' : '' }}>No</option>
    </select><br><br>

    <button type="submit">Update</button>
</form>
