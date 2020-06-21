<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
    </tr>
    </thead>
    <tbody>
    @foreach($cleaners as $c)
        <tr>
            <td>{{ $c->id }}</td>
            <td>{{ $c->username }}</td>
            <td>{{ $c->email }}</td>
        </tr>
    @endforeach
    </tbody>
</table>