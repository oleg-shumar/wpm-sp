<!-- Back-to-top -->
<a href="#top" id="back-to-top"><i class="las la-angle-double-up"></i></a>
<!--Internal Apexchart js-->
<?php if($_GET['page'] == 'sp_integrations'){ ?>
<script src="<?php echo  plugin_dir_url( __FILE__ ); ?>assets/js/apexcharts.js"></script>
<?php } ?>
<script src="<?php echo  plugin_dir_url( __FILE__ ); ?>assets/js/custom.js"></script>
<?php if($_GET['page'] == 'sp_integrations'){ ?>
<script>
    /*--- Apex (#chart) ---*/
    var options = {
        chart: {
            height: 205,
            type: 'radialBar',
            offsetX: 0,
            offsetY: 0,
        },
        plotOptions: {
            radialBar: {
                startAngle: -135,
                endAngle: 135,
                size: 120,
                imageWidth: 50,
                imageHeight: 50,

                track: {
                    strokeWidth: "80%",
                    background: '#ecf0fa',
                },
                dropShadow: {
                    enabled: false,
                    top: 0,
                    left: 0,
                    bottom: 0,
                    blur: 3,
                    opacity: 0.5
                },
                dataLabels: {
                    name: {
                        fontSize: '16px',
                        color: undefined,
                        offsetY: 30,
                    },
                    hollow: {
                        size: "60%"
                    },
                    value: {
                        offsetY: -10,
                        fontSize: '22px',
                        color: undefined,
                        formatter: function (val) {
                            return val + "%";
                        }
                    }
                }
            }
        },
        colors: ['#90566A'],
        fill: {
            type: "gradient",
            gradient: {
                shade: "dark",
                type: "horizontal",
                shadeIntensity: .5,
                gradientToColors: ['#F27AAA'],
                inverseColors: !0,
                opacityFrom: 1,
                opacityTo: 1,
                stops: [0, 100]
            }
        },
        stroke: {
            dashArray: 4
        },
        series: [<?php echo ShelfPlannerCore::getAnalyzedProgress();?>],
        labels: [""]
    };

    window.chart = new ApexCharts(document.querySelector("#chart-sp"), options);
    window.chart.render();

    /**
     * Update progress chart(s)
     */
    function updateCharts() {
        jQuery.get('<?php echo get_home_url();?>');
        jQuery('#sp-total-orders-count, #sp-analyzed-orders-count').html('Updating...');
        jQuery.getJSON('<?php echo  plugin_dir_url( __FILE__ ); ?>ajax.php', function (data) {
            jQuery('#sp-total-orders-count').html(data.total);
            jQuery('#sp-analyzed-orders-count').html(data.analyzed);
            window.chart.updateSeries([data.progress]);
        });
    }

    setInterval(function () {
        updateCharts();
    }, 10 * 1000);
</script>
<?php } ?>
</body>
</html>