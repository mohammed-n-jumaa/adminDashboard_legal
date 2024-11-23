<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Admin Dashboard</title>

  <!-- Plugins CSS -->
  <link rel="stylesheet" href="{{ asset('vendors/feather/feather.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/ti-icons/css/themify-icons.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/css/vendor.bundle.base.css') }}">
  <link rel="stylesheet" href="{{ asset('vendors/datatables.net-bs4/dataTables.bootstrap4.css') }}">
  <link rel="stylesheet" href="{{ asset('css/vertical-layout-light/style.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <link rel="shortcut icon" href="{{ asset('images/favicon.png') }}" />
  <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    .is-invalid {
      border-color: #dc3545;
    }

    .text-danger {
      font-size: 0.875rem;
      margin-top: 0.25rem;
    }

    .chart-container {
      position: relative;
      height: 300px;
      width: 100%;
    }

    .chart-card {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    #lawyers-chart,
    #subscriptions-status-chart,
    #consultations-chart,
    #clients-chart {
      height: 300px;
      max-height: 300px;
    }
  </style>
</head>

<body>
  <div class="container-scroller">
    <!-- Include the header -->
    @include('layouts.header')

    <div class="container-fluid page-body-wrapper">
      <!-- Include the sidebar -->
      @include('layouts.sidebar')

      <div class="main-panel">
        <div class="content-wrapper">
          <!-- Welcome Section -->
          <div class="row">
            <div class="col-md-12 grid-margin">
              <h3 class="font-weight-bold">Welcome, <span style="color: blue;">{{ auth()->user()->name ?? 'Guest' }}</span></h3>
              <h6 class="font-weight-normal mb-0">All systems are running smoothly! You have 
                <span class="text-primary">3 unread alerts!</span>
              </h6>
            </div>
          </div>

          <!-- Filter Section -->
          <div class="row mb-4">
            <div class="col-md-12">
              <form method="GET" action="{{ url('/dashboard') }}">
                <div class="form-inline">
                  <label for="month" class="mr-2">Filter by Month:</label>
                  <select name="month" id="month" class="form-control mr-2" style="width: 200px;">
                    @foreach(range(1, 12) as $month)
                    <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>
                      {{ date('F', mktime(0, 0, 0, $month, 10)) }}
                    </option>
                    @endforeach
                  </select>
                  <button type="submit" class="btn btn-primary">Filter</button>
                </div>
              </form>
            </div>
          </div>

          <!-- Dashboard Statistics -->
          <div class="row">
            <!-- إجمالي الاستشارات -->
            <div class="col-md-3 grid-margin stretch-card">
              <div class="card card-tale">
                <div class="card-body">
                  <p class="mb-4">Total Consultations</p>
                  <p class="fs-30 mb-2">{{ $totalConsultations ?? 0 }}</p>
                </div>
              </div>
            </div>

            <!-- إجمالي المحامين -->
            <div class="col-md-3 grid-margin stretch-card">
              <div class="card card-dark-blue">
                <div class="card-body">
                  <p class="mb-4">Total Lawyers</p>
                  <p class="fs-30 mb-2">{{ $totalLawyers ?? 0 }}</p>
                </div>
              </div>
            </div>

            <!-- إجمالي الاشتراكات مع السعر -->
            <div class="col-md-3 grid-margin stretch-card">
              <div class="card card-light-blue">
                <div class="card-body">
                  <p class="mb-4">Total Subscriptions</p>
                  <p class="fs-30 mb-2">
                    {{ $totalSubscriptions->total_count ?? 0 }}
                    <span style="font-size: 0.8em; color: rgba(255, 255, 255, 0.8);">
                      (${{ number_format($totalSubscriptions->total_price ?? 0, 2) }})
                    </span>
                  </p>
                </div>
              </div>
            </div>

            <!-- إجمالي العملاء -->
            <div class="col-md-3 grid-margin stretch-card">
              <div class="card card-light-danger">
                <div class="card-body">
                  <p class="mb-4">Total Clients</p>
                  <p class="fs-30 mb-2">{{ $totalClients ?? 0 }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Charts Section -->
          <div class="row">
            <!-- Total Lawyers Chart -->
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Total Lawyers</h5>
                  <div class="chart-container">
                    <canvas id="lawyers-chart"></canvas>
                  </div>
                </div>
              </div>
            </div>

            <!-- Subscriptions Status Chart -->
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Subscriptions Status</h5>
                  <div class="chart-container">
                    <canvas id="subscriptions-status-chart"></canvas>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">
              Copyright © {{ now()->year }}. All rights reserved.
            </span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">
              Hand-crafted & made with <i class="ti-heart text-danger ml-1"></i>
            </span>
          </div>
        </footer>
      </div>
    </div>
  </div>

  <!-- Plugins JS -->
  <script src="{{ asset('vendors/js/vendor.bundle.base.js') }}"></script>
  <script src="{{ asset('vendors/chart.js/Chart.min.js') }}"></script>

  <!-- Custom JS -->
  <script>
// Total Lawyers Chart with Monthly Data
var ctxLawyers = document.getElementById('lawyers-chart').getContext('2d');
new Chart(ctxLawyers, {
    type: 'bar',
    data: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        datasets: [{
            label: 'Lawyers Per Month',
            data: [2, 3, 1, 5, 4, 6, 7, 3, 8, 9, 5, 12], // Replace with dynamic data from the backend
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                labels: {
                    color: '#555',
                    font: {
                        size: 12
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    color: '#555',
                    font: {
                        size: 12
                    }
                }
            },
            x: {
                ticks: {
                    color: '#555',
                    font: {
                        size: 12
                    }
                }
            }
        }
    }
});

// Subscriptions Status Chart
var ctxSubscriptionsStatus = document.getElementById('subscriptions-status-chart').getContext('2d');
new Chart(ctxSubscriptionsStatus, {
    type: 'doughnut',
    data: {
        labels: [
            `Active Subscriptions ($${parseFloat({{ $activeSubscriptionsPrice ?? 0 }}).toFixed(2)})`,
            `Expired Subscriptions ($${parseFloat({{ $expiredSubscriptionsPrice ?? 0 }}).toFixed(2)})`
        ],
        datasets: [{
            data: [{{ $activeSubscriptions }}, {{ $expiredSubscriptions }}],
            backgroundColor: [
                'rgba(75, 192, 192, 0.6)',
                'rgba(255, 99, 132, 0.6)'
            ],
            borderColor: [
                'rgba(75, 192, 192, 1)',
                'rgba(255, 99, 132, 1)'
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    color: '#555',
                    font: {
                        size: 12
                    }
                }
            },
            tooltip: {
                callbacks: {
                    label: function (context) {
                        const label = context.label || '';
                        const value = context.raw || 0;
                        return `${label}: ${value} subscriptions`;
                    }
                }
            }
        },
        cutout: '70%'
    }
});

// Consultations Chart
var ctxConsultations = document.getElementById('consultations-chart').getContext('2d');
new Chart(ctxConsultations, {
    type: 'line',
    data: {
        labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
        datasets: [{
            label: 'Consultations Per Month',
            data: [1, 3, 4, 2, 6, 3, 7, 8, 5, 9, 10, 12], // Replace with dynamic data from the backend
            backgroundColor: 'rgba(153, 102, 255, 0.3)',
            borderColor: 'rgba(153, 102, 255, 1)',
            borderWidth: 2,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                labels: {
                    color: '#555',
                    font: {
                        size: 12
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    color: '#555',
                    font: {
                        size: 12
                    }
                }
            },
            x: {
                ticks: {
                    color: '#555',
                    font: {
                        size: 12
                    }
                }
            }
        }
    }
});

// Clients Chart
var ctxClients = document.getElementById('clients-chart').getContext('2d');
new Chart(ctxClients, {
    type: 'pie',
    data: {
        labels: ['Total Clients'],
        datasets: [{
            data: [{{ $totalClients }}], // Replace with dynamic data from the backend
            backgroundColor: [
                'rgba(255, 206, 86, 0.6)'
            ],
            borderColor: [
                'rgba(255, 206, 86, 1)'
            ],
            borderWidth: 2
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    color: '#555',
                    font: {
                        size: 12
                    }
                }
            },
            tooltip: {
                enabled: true
            }
        }
    }
});
  </script>

</body>

</html>
