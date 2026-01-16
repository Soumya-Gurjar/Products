<h2>Add Product</h2>

@if ($errors->any())
    <ul style="color:red">
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif

<form method="POST" action="{{ route('products.store') }}">
    @csrf

    Name: <input type="text" name="name"><br><br>
    SKU: <input type="text" name="sku"><br><br>
    Price: <input type="number" step="0.01" name="price"><br><br>
    Stock: <input type="number" name="stock"><br><br>

    Active:
    <select name="is_active">
        <option value="1">Yes</option>
        <option value="0">No</option>
    </select><br><br>

    <button type="submit">Save</button>
</form>
