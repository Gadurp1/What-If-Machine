import Chart from 'chart.js';

export default{
  template:`
    <div>
      <canvas style="height:250px" v-el:canvas></canvas>
      <div class="legend"> </div>
    </div>
  `,

  props:{
    labels:{},
    values: {},
    color:{
      default:'red'
    },
    label:{
      default:'Some Chart'
    }
  },

  data(){
    return { legend: '' }
  },

  ready(){

    var data = {

      labels: this.labels,
      datasets: [
          {
              label: this.label,
              fillColor: "red",
              strokeColor: "red",
              pointColor: "rgba(220,220,220,1)",
              pointStrokeColor: "#fff",
              pointHighlightFill: "#fff",
              pointHighlightStroke: "rgba(220,220,220,1)",
              data: this.values
          }
      ],
    };

    var newChart = new Chart(
        this.$els.canvas.getContext("2d") , {
        type: "bar",
        gridLines: false,
        data: data
    });

    this.legend = newChart.generateLegend();
  }
}
