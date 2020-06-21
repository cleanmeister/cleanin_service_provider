<table>
    <thead>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Email</th>
    </tr>
    </thead>
    <tbody>
    @foreach($service_providers as $sps)
        <tr>
            <td>{{ $sps->id }}</td>
            <td>{{ $sps->username }}</td>
            <td>{{ $sps->email }}</td>

        </tr>
    @endforeach
    </tbody>
</table>