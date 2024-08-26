<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Dashboard</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container my-4">
        <h1 class="mb-4 text-center">Inventory Dashboard</h1>
        
        <!-- Environmental Metrics -->
        <div class="row text-center mb-4">
            <div class="col-md-4">
                <img src="/assets/images/leaf-icon.png" alt="CO2 Saved" style="width: 50px;">
                <h2 id="co2-saved">Loading...</h2>
                <p>CO2 Saved (tons)</p>
            </div>
            <div class="col-md-4">
                <img src="/assets/images/recycle-icon.png" alt="Landfill Space Saved" style="width: 50px;">
                <h2 id="landfill-saved">Loading...</h2>
                <p>Landfill Space Saved (m³)</p>
            </div>
            <div class="col-md-4">
                <img src="/assets/images/lightbulb-icon.png" alt="Energy Saved" style="width: 50px;">
                <h2 id="energy-saved">Loading...</h2>
                <p>Energy Saved (MWh)</p>
            </div>
        </div>

        <!-- Inventory Metrics -->
        <div class="row text-center mb-4">
            <div class="col-md-3">
                <h2 id="total-parts">Loading...</h2>
                <p>Total Parts</p>
            </div>
            <div class="col-md-3">
                <h2 id="available-parts">Loading...</h2>
                <p>Available Parts</p>
            </div>
            <div class="col-md-3">
                <h2 id="sold-parts">Loading...</h2>
                <p>Sold Parts</p>
            </div>
            <div class="col-md-3">
                <h2 id="avg-days-in-inventory">Loading...</h2>
                <p>Average Days in Inventory</p>
            </div>
        </div>
        
        <!-- Charts -->
        <div class="row mt-4">
            <div class="col-md-6 mb-4">
                <h4 class="text-center">Most Recycled Parts</h4>
                <canvas id="recycled-parts-chart"></canvas>
            </div>
            <div class="col-md-6 mb-4">
                <h4 class="text-center">Parts by Vehicle Make</h4>
                <canvas id="parts-by-make-chart"></canvas>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            fetch('/api/insights')
                .then(response => response.json())
                .then(data => {
                    // Debugging logs to check element presence
                    console.log('Data fetched:', data);
                    
                    // Ensure the elements exist before setting their content
                    if (document.getElementById('total-parts')) {
                        document.getElementById('total-parts').textContent = data.total_parts || 'N/A';
                    }
                    if (document.getElementById('available-parts')) {
                        document.getElementById('available-parts').textContent = data.available_parts || 'N/A';
                    }
                    if (document.getElementById('sold-parts')) {
                        document.getElementById('sold-parts').textContent = data.sold_parts || 'N/A';
                    }
                    if (document.getElementById('avg-days-in-inventory')) {
                        document.getElementById('avg-days-in-inventory').textContent = data.avg_days_in_inventory || 'N/A';
                    }

                    if (document.getElementById('co2-saved')) {
                        document.getElementById('co2-saved').textContent = data.co2_saved || 'N/A';
                    }
                    if (document.getElementById('landfill-saved')) {
                        document.getElementById('landfill-saved').textContent = data.landfill_saved || 'N/A';
                    }
                    if (document.getElementById('energy-saved')) {
                        document.getElementById('energy-saved').textContent = data.energy_saved || 'N/A';
                    }

                    // Chart for most recycled parts
                    const recycledPartsCtx = document.getElementById('recycled-parts-chart').getContext('2d');
                    new Chart(recycledPartsCtx, {
                        type: 'bar',
                        data: {
                            labels: data.most_recycled_parts.map(part => part.part_name),
                            datasets: [{
                                label: 'Recycled Count',
                                data: data.most_recycled_parts.map(part => part.recycled_count),
                                backgroundColor: 'rgba(144, 228, 193)',  // Light teal color for all bars
                                borderColor: 'rgba(75, 192, 192, 1)',  // Teal border
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

                    // Chart for parts by vehicle make
                    const partsByMakeCtx = document.getElementById('parts-by-make-chart').getContext('2d');
                    const numPartsByMake = data.parts_by_make.length;
                    const partsByMakeColors = generateColors(numPartsByMake);
                    new Chart(partsByMakeCtx, {
                        type: 'pie',
                        data: {
                            labels: data.parts_by_make.map(make => make.vehicle_make),
                            datasets: [{
                                label: 'Parts Count',
                                data: data.parts_by_make.map(make => make.count),
                                backgroundColor: partsByMakeColors,
                                borderColor: partsByMakeColors.map(color => color.replace('0.2', '1')),
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                        }
                    });
                })
                .catch(error => console.error('Error fetching insights data:', error));
        });

        // Function to generate a set of distinct colors
        function generateColors(numColors) {
            const colors = [];
            const step = 360 / numColors;
            for (let i = 0; i < numColors; i++) {
                const hue = i * step;
                colors.push(`hsl(${hue}, 70%, 70%)`);
            }
            return colors;
        }
    </script>
</body>
</html>




