@extends('layouts.main')
@section('css')

@endsection

@section('content')
    <style>
        .dash-brand-logo{
            height: 100px;
            width: auto;
        }
    </style>

    <div class="app-content content">
        <div class="content-overlay"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>
            <div class="content-body">
                <section class="users-list-wrapper">
                    <div class="card">
                        <div class="card-content">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="media">
                                            <div class="media-left pr-1">
                                                <img class="media-object img-xl" src="{{ asset('img/No_profile-image.png') }}" alt="Generic placeholder image">
                                            </div>
                                            <div class="media-body">
                                                <h6 class="text-bold-500 pt-1 mb-0">{{ auth()->user()->name }}</h6>
                                                <h6 class="text-bold-500 pt-1 mb-0">{{ auth()->user()->account_type }} account</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-xl-3 col-lg-6 col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="media align-items-stretch">
                                        <div class="p-2 text-center bg-primary bg-darken-2">
                                            <i class="icon-camera font-large-2 white"></i>
                                        </div>
                                        <div class="p-2 bg-gradient-x-primary white media-body">
                                            <h5>Current Balance</h5>
                                            <h5 class="text-bold-400 mb-0"><i class="feather icon-plus"></i>BDT {{ Auth::user()->balance }} /-</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="media align-items-stretch">
                                        <div class="p-2 text-center bg-danger bg-darken-2">
                                            <i class="icon-user font-large-2 white"></i>
                                        </div>
                                        <div class="p-2 bg-gradient-x-danger white media-body">
                                            <h5>Total Deposit</h5>
                                            <h5 class="text-bold-400 mb-0"><i class="feather icon-arrow-up"></i>BDT {{ $deposit }} /-</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="media align-items-stretch">
                                        <div class="p-2 text-center bg-warning bg-darken-2">
                                            <i class="icon-basket-loaded font-large-2 white"></i>
                                        </div>
                                        <div class="p-2 bg-gradient-x-warning white media-body">
                                            <h5>Total Withdrawal</h5>
                                            <h5 class="text-bold-400 mb-0"><i class="feather icon-arrow-down"></i>BDT {{ $withdrawal }} /-</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-6 col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="media align-items-stretch">
                                        <div class="p-2 text-center bg-success bg-darken-2">
                                            <i class="icon-wallet font-large-2 white"></i>
                                        </div>
                                        <div class="p-2 bg-gradient-x-success white media-body">
                                            <h5>Total Amount</h5>
                                            <h5 class="text-bold-400 mb-0"><i class="feather icon-arrow-up"></i>BDT {{ $deposit + $withdrawal }} /-</h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row match-height">
                        <div class="col-xl-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Recent transactions</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                    </div>
                                    <div class="table-responsive">
                                        <table id="recent-orders" class="table table-hover mb-0 ps-container ps-theme-default">
                                            <thead>
                                            <tr>
                                                <th>Transaction Id</th>
                                                <th>Date</th>
                                                <th>Transaction Type</th>
                                                <th>Amount</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($records as $row)
                                                    <tr>
                                                        <td class="text-truncate">{{ $row->id }}</td>
                                                        <td class="text-truncate">{{ date('d-m-Y',strtotime($row->date)) }}</td>
                                                        @if($row->transaction_type == 'Deposit')
                                                            <td class="text-truncate"><span class="badge badge-success">Deposit</span></td>
                                                        @else
                                                            <td class="text-truncate"><span class="badge badge-warning">Withdrawal</span></td>
                                                        @endif
                                                        <td class="text-truncate">BDT {{ $row->amount }} /-</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td align="center" colspan="4">No Transaction Found</td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    {{ $records->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

@endsection

@section('page-js')

@endsection

@section('js')


@endsection
