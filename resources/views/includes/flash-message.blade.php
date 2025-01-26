
@if ($message = session('success'))
    <div class="d-flex align-items-center alert alert-success alert-block">
        <button type="button" class="close btn btn-xs btn-success mr-2" title="fermer ce message d'alerte !" data-bs-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
    </div>
@endif


@if ($message = session('error'))

<div class="d-flex align-items-center alert alert-danger alert-block">
	<button type="button" class="close btn btn-xs btn-danger mr-2" title="fermer ce message d'alerte !" data-bs-dismiss="alert">×</button>
        <strong>{{ $message }}</strong>
</div>
@endif


@if ($message = session('warning'))

<div class="d-flex align-items-center alert alert-warning alert-block">

	<button type="button" class="close btn btn-xs btn-warning mr-2" title="fermer ce message d'alerte !" data-bs-dismiss="alert">×</button>

	<strong>{{ $message }}</strong>

</div>

@endif


@if ($message = session('info'))

<div class="d-flex align-items-center  alert alert-info alert-block">

	<button type="button" class="close" title="fermer ce message d'alerte !" data-bs-dismiss="alert">×</button>

	<strong>{{ $message }}</strong>

</div>

@endif


@if ($errors->any())

<div class="alert alert-danger">

	<button type="button" class="close btn btn-xs btn-danger mr-2" title="fermer ce message d'alerte !" data-bs-dismiss="alert">×</button>

	Please check the form below for errors

</div>

@endif

<style>
    section#content>.alert{
        margin: 0;
        position: fixed;
        top: 25px;
        z-index: 1000;
        float: right;
        width: fit-content;
        align-self: end;
        right: 10px;
    }

    button.close{
        margin-right: 20px;
    }

    
</style>
