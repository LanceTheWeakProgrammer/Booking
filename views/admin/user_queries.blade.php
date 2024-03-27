@extends('admin.app')

@section('title', 'Admin Panel')

@section('header')

@endsection

@section('content')

<style>
    td {
        cursor: pointer;
    }
</style>

<div class="container-fluid" id="main-content">
    <div class="row">
        <div class="col-lg-10 ms-auto overflow-hidden p-3">
            <h3 class="mb-4">User Queries</h3>

            <div class="card border-0 shadow-sm mb-4 bg-light">
                <div class="card-body">

                    <form method="POST" action="{{ route('userqueries.update') }}">
                        @csrf

                        <div class="text-end mb-4">
                            <button type="submit" name="action" value="all" class="btn custom-bg text-white rounded-pill shadow-none btn-sm me-2">
                                <i class="bi bi-check-all text-white"></i> Mark all as read
                            </button>
                            <button type="submit" name="delete" value="all" class="btn btn-secondary rounded-pill shadow-none btn-sm">
                                <i class="bi bi-x-lg"></i> Delete all
                            </button>
                        </div>

                        <div class="table-responsive-md" style="height: 375px; overflow-y: scroll;">
                            <table class="table table-light table-hover border border-0 border-secondary">
                                <thead class="table-secondary">
                                    <tr class="bg-light text-dark">
                                        <th scope="col">#</th>
                                        <th scope="col">Name</th>
                                        <th scope="col">Email</th>
                                        <th scope="col" width="15%">Subject</th>
                                        <th scope="col" width="25%">Message</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($userQueries as $query)
                                        <tr onclick="showModal('{{ $query->name }}', '{{ $query->subject }}', '{{ $query->message }}', event)">
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $query->name }}</td>
                                            <td>{{ $query->email }}</td>
                                            <td>{{ $query->subject }}</td>
                                            <td>{{ strlen($query->message) > 30 ? substr($query->message, 0, 30) . '...' : $query->message }}</td>
                                            <td>{{ $query->date }}</td>
                                            <td>
                                                @if($query->action != 1)
                                                    <button type="submit" name="action" value="{{ $query->queryID }}" class="btn btn-sm rounded-pill custom-bg text-white me-2">Mark as read</button>
                                                @endif
                                                <button type="submit" name="delete" value="{{ $query->queryID }}" class="btn btn-sm rounded-pill btn-secondary">Delete</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>     
    </div>
</div>

<div class="modal fade" id="userQueriesModal" tabindex="-1" aria-labelledby="userQueriesModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header custom-bg2">
                <h5 class="modal-title" id="userQueriesModalLabel">User Query Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body custom-bg2">
                <p>Name: <br> <span id="modalName"></span></p>
                <p>Subject:</strong> <br> <span id="modalSubject"></span></p>
                <p>Message: <br> <span id="modalMessage"></span></p>
            </div>
            <div shadow-none class="modal-footer custom-bg2">
            </div>
        </div>
    </div>
</div>



@endsection

@push('scripts')
<script>
function showModal(name, subject, message, event) {
    if (!event.target.matches('button')) {
        document.getElementById('modalName').innerText = name;
        document.getElementById('modalSubject').innerText = subject;
        document.getElementById('modalMessage').innerText = message;

        var myModal = new bootstrap.Modal(document.getElementById('userQueriesModal'));
        myModal.show();
    }
}

    @if(session('success'))
        alert('success', '{{ session('success') }}');
    @endif
</script>
    
@endpush  