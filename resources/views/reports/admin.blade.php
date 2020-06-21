<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
    </tr>
    </thead>
    <tbody>
    @foreach($admins as $a)
        <tr>
            <td>{{ $a->id }}</td>
            <td>{{ $a->username }}</td>
            <td>{{ $a->email }}</td>

        </tr>
    @endforeach
    </tbody>
</table>