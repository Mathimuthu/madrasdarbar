@extends('company.admin.main')

@section('title', 'Charges')

@section('content')

    <div class="promotion-discount-index">

        <section>

            <main>

                @if (session('success-message'))
                    <div class="success-message left-green">
                        <i class='bx bxs-check-circle'></i>
                        <div class="text">
                            <span>Success</span>
                            <span class="message">{{ session('success-message') }}</span>
                        </div>
                    </div>
                @endif

                <div class="header">
                    <div class="left">
                        <h1>Charges</h1>
                    </div>
                    <a href="{{ route('charges.create') }}" class="create">
                        <span>Create New Charges</span>
                    </a>
                </div>

                <div class="index-section">

                    <div class="container">

                        <div class="header">
                            <i class='bx bx-purchase-tag'></i>
                            <h3>Charges</h3>
                            <i class='bx bx-filter'></i>
                            <form action="/" method="GET" id="search-form">
                                <div class="search-field">
                                    <i class='bx bx-search' id="search-button"></i>
                                    <input type="text" name="search" placeholder="Search" value="{{ old('search') }}">
                                </div>
                            </form>
                        </div>

                        <table>

                            <thead>
                                <tr>
                                    <th>S.No</th>
                                    <th>Name</th>
                                    <th>Amount</th>
                                    <th>Type</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Updated At</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($charges as $charge)
                                    <tr>
                                        <td>{{ $charges->firstItem() + $loop->index }}</td>
                                        <td>{{ $charge->name }}</td>
                                        <td>
                                            @if($charge->type == 1)
                                                {{ number_format($charge->amount, 2) }}
                                            @elseif($charge->type == 2)
                                                {{ number_format($charge->amount, 2) }}%
                                            @endif
                                        </td>
                                        <td>
                                            @if($charge->type == 1)
                                                Fixed Amount
                                            @elseif($charge->type == 2)
                                                Percentage
                                            @endif
                                        </td>
                                        <td>{{ $charge->location->location_name }}</td>
                                        @if($charge->status == 1)
                                        <td>Active</td>
                                        @else
                                        <td>Deactive</td>
                                        @endif
                                        <td>{{ $charge->created_at }}</td>
                                        <td>{{ $charge->updated_at }}</td>
                                        <td><a href="{{ route('charges.show',$charge->id) }}"><i class='bx bxs-pencil'></i><span>Edit</span></a></td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>

                        <div class="pagination">
                            <div class="count">Showing {{ $charges->firstItem() }} to {{ $charges->lastItem() }}
                                out of {{ $charges->total() }} results</div>
                            <div class="pagination-number">
                                <div class="page-number">{{ $charges->render('company.partials.paginator') }}</div>
                            </div>
                        </div>
                        
                    </div>

                </div>

            </main>

        </section>

    </div>

@endsection
