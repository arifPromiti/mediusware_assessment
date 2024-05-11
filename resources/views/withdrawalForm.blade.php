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

                    <div class="row match-height">
                        <div class="col-xl-12 col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">Recent Withdrawal</h4>
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <form action="{{ route('withdraw.add') }}" method="post">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group @error('withdrawal_balance') has-error @enderror">
                                                        <label for="withdrawal_balance">Withdrawal Amount</label>
                                                        <input type="text" class="form-control @error('withdrawal_balance') is-invalid @enderror" value="{{ old('withdrawal_balance')??0 }}" name="withdrawal_balance" id="withdrawal_balance"  required>
                                                        @error('withdrawal_balance')
                                                            <p class="help-block">{{ $errors->first('withdrawal_balance') }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <button type="submit" class="btn btn-success">Add</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="table-responsive">
                                        <table id="recent-orders" class="table table-hover mb-0 ps-container ps-theme-default">
                                            <thead>
                                            <tr>
                                                <th>Transaction Id</th>
                                                <th>Date</th>
                                                <th>Transaction Type</th>
                                                <th>Amount</th>
                                                <th>Fee</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($records as $row)
                                                    <tr>
                                                        <td class="text-truncate">{{ $row->id }}</td>
                                                        <td class="text-truncate">{{ date('d-m-Y',strtotime($row->date)) }}</td>
                                                        <td class="text-truncate"><span class="badge badge-warning">Withdrawal</span></td>
                                                        <td class="text-truncate">BDT {{ $row->amount }} /-</td>
                                                        <td class="text-truncate">BDT {{ $row->amount }} /-</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td align="center" colspan="5">No Transaction Found</td>
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