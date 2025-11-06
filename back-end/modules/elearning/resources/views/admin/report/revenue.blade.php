@extends('core::admin.master')

@section('meta_title', __('elearning::report.revenue_report'))

@section('page_title', __('elearning::report.revenue_report'))

@section('page_subtitle', __('elearning::report.view_report'))

@section('content')
    <div class="content">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="card-title">{{ __('elearning::report.date_range') }}</h3>
                    </div>
                    <div class="col-md-6 text-right">
                        <button type="button" class="btn btn-primary" id="refresh-btn">
                            <i class="fas fa-sync"></i> {{ __('core::button.refresh') }}
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="start-date">{{ __('elearning::report.start_date') }}</label>
                            <input type="date" id="start-date" class="form-control" value="{{ date('Y-m-01') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="end-date">{{ __('elearning::report.end_date') }}</label>
                            <input type="date" id="end-date" class="form-control" value="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="interval">{{ __('elearning::report.interval') }}</label>
                            <select id="interval" class="form-control">
                                <option value="daily">{{ __('elearning::report.daily') }}</option>
                                <option value="weekly">{{ __('elearning::report.weekly') }}</option>
                                <option value="monthly">{{ __('elearning::report.monthly') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('elearning::report.revenue_over_time') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="height: 300px;">
                            <canvas id="revenue-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('elearning::report.revenue_by_payment_method') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="height: 300px;">
                            <canvas id="payment-method-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('elearning::report.top_courses_by_revenue') }}</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>{{ __('elearning::course.model_name_single') }}</th>
                                <th>{{ __('elearning::report.total_revenue') }}</th>
                            </tr>
                        </thead>
                        <tbody id="top-courses-body">
                            <tr>
                                <td colspan="2" class="text-center">{{ __('elearning::report.loading_data') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let revenueChart = null;
        let paymentMethodChart = null;
        
        function fetchRevenueData() {
            const startDate = document.getElementById('start-date').value;
            const endDate = document.getElementById('end-date').value;
            const interval = document.getElementById('interval').value;
            
            fetch(`/api/v1/elearning/admin/reports/revenue?start_date=${startDate}&end_date=${endDate}&interval=${interval}`, {
                headers: {
                    'Authorization': `Bearer ${window.Laravel.apiToken}`,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                updateRevenueChart(data.revenue_by_interval);
                updatePaymentMethodChart(data.revenue_by_payment_method);
                updateTopCoursesTable(data.top_courses_by_revenue);
            })
            .catch(error => console.error('Error fetching revenue data:', error));
        }
        
        function updateRevenueChart(revenueData) {
            const labels = revenueData.map(item => item.label || item.date);
            const values = revenueData.map(item => item.total);
            
            if (revenueChart) {
                revenueChart.destroy();
            }
            
            const ctx = document.getElementById('revenue-chart').getContext('2d');
            revenueChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: '{{ __("elearning::report.revenue") }}',
                        data: values,
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value;
                                }
                            }
                        }
                    }
                }
            });
        }
        
        function updatePaymentMethodChart(paymentData) {
            const labels = paymentData.map(item => item.payment_method);
            const values = paymentData.map(item => item.total);
            
            if (paymentMethodChart) {
                paymentMethodChart.destroy();
            }
            
            const ctx = document.getElementById('payment-method-chart').getContext('2d');
            paymentMethodChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: values,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.7)',
                            'rgba(54, 162, 235, 0.7)',
                            'rgba(255, 206, 86, 0.7)',
                            'rgba(75, 192, 192, 0.7)',
                            'rgba(153, 102, 255, 0.7)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = Math.round((value / total) * 100);
                                    return `${context.label}: $${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }
        
        function updateTopCoursesTable(coursesData) {
            const tableBody = document.getElementById('top-courses-body');
            tableBody.innerHTML = '';
            
            if (coursesData.length === 0) {
                const row = document.createElement('tr');
                row.innerHTML = `<td colspan="2" class="text-center">{{ __('elearning::report.no_data') }}</td>`;
                tableBody.appendChild(row);
                return;
            }
            
            coursesData.forEach(course => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${course.name}</td>
                    <td>$${parseFloat(course.total).toFixed(2)}</td>
                `;
                tableBody.appendChild(row);
            });
        }
        
        // Initialize
        fetchRevenueData();
        
        // Event listeners
        document.getElementById('refresh-btn').addEventListener('click', fetchRevenueData);
        document.getElementById('interval').addEventListener('change', fetchRevenueData);
        document.getElementById('start-date').addEventListener('change', fetchRevenueData);
        document.getElementById('end-date').addEventListener('change', fetchRevenueData);
    });
</script>
@endpush
