<!DOCTYPE HTML>
<html>
    <head>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <style>
        section {
            position: relative;
            width: 600px;
        }
        </style>
    </head>
    <body>

        <section>
            <canvas id="myChart"></canvas>
        </section>

        <script>

            const DATA_COUNT = 7;
            const NUMBER_CFG = {count: DATA_COUNT, min: -100, max: 100};

            const labels = Utils.months({count: 7});
            const data = {
                labels: labels,
                datasets: [
                    {
                    label: 'Dataset 1',
                    data: Utils.numbers(NUMBER_CFG),
                    borderColor: Utils.CHART_COLORS.red,
                    backgroundColor: Utils.transparentize(Utils.CHART_COLORS.red, 0.5),
                    },
                    {
                    label: 'Dataset 2',
                    data: Utils.numbers(NUMBER_CFG),
                    borderColor: Utils.CHART_COLORS.blue,
                    backgroundColor: Utils.transparentize(Utils.CHART_COLORS.blue, 0.5),
                    }
                ]
            };

            const config = {
                type: 'line',
                data: data,
                options: {
                    responsive: true,
                    plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Chart.js Line Chart'
                    }
                    }
                },
            };

           var myChart = new Chart(
                document.getElementById('myChart'),
                config
            );

        </script>
    </body>
</html>