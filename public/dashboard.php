<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insights Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container mt-4">
        <header>
            <h1 class="text-center">Insights Dashboard</h1>
        </header>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Parts</h5>
                        <p class="card-text" id="total-parts">Loading...</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Available Parts</h5>
                        <p class="card-text" id="available-parts">Loading...</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Sold Parts</h5>
                        <p class="card-text" id="sold-parts">Loading...</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Fulfilled Requests</h5>
                        <p class="card-text" id="fulfilled-requests">Loading...</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Average Days in Inventory</h5>
                        <p class="card-text" id="avg-days-in-inventory">Loading...</p>
                    </div>
                </div>
            </div>
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Most Recycled Parts</h5>
                        <canvas id="recycled-parts-chart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Parts by Vehicle Make</h5>
                        <canvas id="parts-by-make-chart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        fetch('/api/insights')
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-parts').textContent = data.total_parts;
                document.getElementById('available-parts').textContent = data.available_parts;
                document.getElementById('sold-parts').textContent = data.sold_parts;
                document.getElementById('fulfilled-requests').textContent = data.fulfilled_requests;
                document.getElementById('avg-days-in-inventory').textContent = data.avg_days_in_inventory;

                const recycledPartsCtx = document.getElementById('recycled-parts-chart').getContext('2d');
                const partsByMakeCtx = document.getElementById('parts-by-make-chart').getContext('2d');

                new Chart(recycledPartsCtx, {
                    type: 'bar',
                    data: {
                        labels: data.most_recycled_parts.map(part => part.part_name),
                        datasets: [{
                            label: 'Recycled Count',
                            data: data.most_recycled_parts.map(part => part.recycled_count),
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                new Chart(partsByMakeCtx, {
                    type: 'pie',
                    data: {
                        labels: data.parts_by_make.map(make => make.vehicle_make),
                        datasets: [{
                            label: 'Parts Count',
                            data: data.parts_by_make.map(make => make.count),
                            backgroundColor: ['rgba(255, 99, 132, 0.2)', 'rgba(54, 162, 235, 0.2)', 'rgba(255, 206, 86, 0.2)', 'rgba(75, 192, 192, 0.2)', 'rgba(153, 102, 255, 0.2)', 'rgba(255, 159, 64, 0.2)'],
                            borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(tooltipItem) {
                                        return tooltipItem.label + ': ' + tooltipItem.raw;
                                    }
                                }
                            }
                        }
                    }
                });
            })
            .catch(error => console.error('Error fetching insights data:', error));
    </script>
</body>
</html>
