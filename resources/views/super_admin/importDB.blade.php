<html>
    @if (isset($success))
        {{ $success }}
    @endif
    <form action="/SA/import" method="POST" enctype="multipart/form-data">
        @csrf
        <div>
            <input type="text" name="name">
            <input type="file" name="file">
        </div>
        <!-- Save changes button-->
        <button class="btn btn-primary" type="submit">Save changes</button>
    </form>
</html>
