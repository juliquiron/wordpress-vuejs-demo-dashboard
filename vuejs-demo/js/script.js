var dashboardChart = Vue.component('line-chart', {
  extends: VueChartJs.Line,
  mounted () {
    this.renderChart({
      labels: ['label 1', 'label 2'],
      datasets: [{
        label: 'trending proucts',
        data: [32, 45]
      }]
    }, {responsive: true})
  }
});
var dashboardManager = new Vue({
  el: '#app',

  data: {
    topProducts: [ ],
    brandProducts: [ ]
  }
})
// Total sales
// price
// featured
jQuery(document).ready(function () {
    jQuery.each(topProducts, function (id, data) {
        dashboardManager.topProducts.push({
            label: id,
            post_title: data.obj.post_title,
            total_sales: data.meta.total_sales[0],
            brand: data.brand,
            type: data.type
        });
    })
    // Sort by total sales
    dashboardManager.topProducts.sort(function (a, b) {
      return b.total_sales - a.total_sales;
    });
    jQuery.each(brands, function (id, data) {
        dashboardManager.brandProducts.push({
            label: id,
            post_title: data.obj.post_title,
            total_sales: data.meta.total_sales[0],
            brand: data.brand,
            type: data.type
        });
    })

    //dashboardChart.renderChart({
        //labels: ['new 1', 'new 2'],
        //datasets: [{
            //label: 'trending proucts',
            //data: [32, 45]
        //}]
    //}, {responsive: true});

});
