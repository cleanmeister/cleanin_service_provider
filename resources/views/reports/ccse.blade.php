<table>
    <thead>
    <tr>
        <th colspan="3">Cleaner</th>
        <th colspan="3">Schedule</th>
        <th colspan="3">Client</th>

        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $d)
        <tr>
            <td>{{ $d->cleaner_firstname }}</td>
            <td>{{ $d->cleaner_middlename }}</td>
            <td>{{ $d->cleaner_lastname }}</td>
            <td>{{ $d->service_name }}</td>
            <td>{{ $d->day_desc }}</td>
            <td>{{ $d->start_time }}</td>
            <td>{{ $d->end_time }}</td>
            <td>{{ $d->client_firstname }}</td>
            <td>{{ $d->client_middlename }}</td>
            <td>{{ $d->client_lastname }}</td>
            <td>
                @if($d->status == 1)Pending@endif
                @if($d->status == 2)Approved@endif
                @if($d->status == 3)Completed@endif
                @if($d->status == 4)Rejected@endif
                @if($d->status == 5)Cancel@endif
            </td>
        </tr>
    @endforeach
    </tbody>
</table>