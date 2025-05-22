@extends('master')
@section('content')
<div class="modal-body">
    <form action="{{ route('clients.update', $client->id ) }}" method="POST">
        @csrf
        @method('PUT')

        <label>Nama Client</label>
        <input type="text" name="name" value="{{ $client->name }}" class="form-control">

        <label>Alamat</label>
        <input type="text" name="address" value="{{ $client->address }}" class="form-control">

        <label>PIC</label>
        <input type="text" name="pic" value="{{ $client->pic }}" class="form-control">

        <label>Mobile Phone</label>
        <input type="text" name="mobile_phone" value="{{ $client->mobile_phone }}" class="form-control">

        <label>Gmail</label>
        <input type="email" name="gmail" value="{{ $client->gmail }}" class="form-control">

        <br>
        <button type="submit" class="btn btn-success">Simpan</button>
    </form>
</div>

@endsection
