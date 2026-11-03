@extends('company.staff.main')

@section('title', 'Dashboard')

@section('content')

    <section>

        <div class="dashboard">

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

                <div class="content">

                    <div class="header">
                        <h1>Dashboard</h1>
                    </div>

                    <div class="statistic">

                        <div class="item1">
                            <i class='bx bxs-cart'></i>
                            <span>Total Orders</span>
                        </div>

                        <div class="item2">
                            <i class='bx bxl-product-hunt'></i>
                            <span>Total Products</span>
                        </div>

                        <div class="item3">
                            <i class='bx bxs-dollar-circle'></i>
                            <span>My Savings</span>
                        </div>

                    </div>
                </div>

            </main>

        </div>
        <style>
            /* Mobile Responsive Styles */
            @media (max-width: 768px) {
                .dashboard {
                    padding: 15px 10px;
                    margin: 0;
                }
                
                .content {
                    margin: 0;
                    padding: 0;
                }
                
                .header h1 {
                    font-size: 1.8rem;
                    text-align: center;
                    margin-bottom: 20px;
                }
                
                .statistic {
                    display: flex;
                    flex-direction: column;
                    gap: 15px;
                    padding: 0 10px;
                }
                
                .statistic > div {
                    width: 100%;
                    padding: 25px 20px;
                    margin: 0;
                    text-align: center;
                }
                
                .statistic i {
                    font-size: 2rem;
                    margin-bottom: 10px;
                }
                
                .statistic span {
                    font-size: 1.1rem;
                }
                
                .success-message {
                    margin: 10px;
                    padding: 15px;
                    font-size: 0.9rem;
                }
                
                .success-message .text {
                    flex-direction: column;
                    gap: 5px;
                }
            }
            
            /* Small Mobile Devices */
            @media (max-width: 480px) {
                .header h1 {
                    font-size: 1.5rem;
                }
                
                .statistic > div {
                    padding: 20px 15px;
                }
                
                .statistic i {
                    font-size: 1.8rem;
                }
            }
        </style>
    </section>

@endsection
