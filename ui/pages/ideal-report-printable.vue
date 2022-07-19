<template>
  <div>
    <client-only>
      <div class="report_preview" v-if="report">
        <div class="report_banner" style="background-image: url('images/report_banner.jpg')">
          <div class="report_excerpt">
            <img src="https://craydel.fra1.cdn.digitaloceanspaces.com/logo/craydel.svg" alt="craydel">
            <h2>Career Match Assessment</h2>
          </div>
        </div>
        <div class="report_section" v-if="getPage(report,1)">
          <div class="report_user_dt">
            <table>
              <tbody>
              <tr>
                <th>Name:</th>
                <td>{{ getPage(report, 1).fname }} {{ getPage(report, 1).lname }}</td>
              </tr>
              <tr>
                <th>Phone No:</th>
                <td>{{ getPage(report, 1).mobile }}</td>
              </tr>
              <tr>
                <th>Email Id:</th>
                <td>{{ getPage(report, 1).email }}</td>
              </tr>
              <tr>
                <th>Date:</th>
                <td>{{ getPage(report, 1).date }}</td>
              </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="top_three_career" v-if="getPage(report,1)">
          <h3>How will the Career Match Assessment report help?</h3>
        </div>
        <div class="report_section" v-if="getPage(report,1)">
          <ul class="report_list" v-if="getPage(report,1).sections">
            <li v-for="item in getPage(report,1).sections[0].bullets">{{ item.bullet }}</li>
          </ul>
        </div>
        <div class="footer_first_page">
          The report should be taken as an an indicator or a facilitator and as an ongoing dialogue on career choices
          and
          effective career planning in life.
        </div>

        <div class="report_page">
          <div class="report_header" v-if="getPage(report,1)">
            <div class="report_header_left">
              <small class="report_logo"><img src="~/assets/images/craydel.svg" alt="Craydel"></small>
              <p>Career Match Assessment Report</p>
            </div>
            <div class="report_header_right">{{ getPage(report, 1).fname }} |
              {{ new Date(getPage(report, 1).date).toLocaleDateString() }}
            </div>
          </div>
          <div class="report_section">
            <template v-if="getPage(report, 2)">
              <h3 class="report_title">Here Are Your Top 3 Ideal Careers</h3>
              <template v-if="getPage(report, 2).sections">
                <div class="report_ideal_grid" v-for="(item,index) in getPage(report, 2).sections[0].sectors">
                  <div class="report_number">0{{ index + 1 }}</div>
                  <div class="report_grid_excerpt">
                    <h4>{{ item.name }}</h4>
                    <p>
                      {{ item.description }}
                    </p>
                    <ul class="columnize_list" v-if="item.job_roles">
                      <li v-for="role in item.job_roles">{{ role.option }}</li>
                    </ul>
                  </div>
                </div>
              </template>
            </template>
            <template v-if="getPage(report, 3)">
              <h3 class="report_title" v-if="getPage(report, 3).sections">{{
                  getPage(report, 3).sections[0].title
                }}</h3>
              <div class="report_text_sheet" v-if="getPage(report, 3).sections">
                <template v-for="item in getPage(report, 3).sections[0].paragraphs">
                  <p>{{ item.paragraph }}</p>
                  <ul class="check_list" v-if="item.bullets">
                    <li v-for="bullet in item.bullets">
                      {{ bullet.bullet }}
                    </li>
                  </ul>
                </template>

              </div>
            </template>

            <template v-if="getPage(report, 4)">
              <h3 class="report_title" v-if="getPage(report, 4).sections">{{ getPage(report, 1).fname }}'s
                {{ getPage(report, 4).sections[0].title }}</h3>
              <div class="report_text_sheet" v-if="getPage(report, 4).sections">
                <template v-for="item in getPage(report, 4).sections[0].motivators">
                  <h4>{{ item.name }}</h4>
                  <p>{{ item.description }}</p>
                </template>
              </div>
            </template>

            <template v-if="getPage(report, 5)">
              <template v-if="getPage(report, 5).sections">
                <h3 class="report_title">{{ getPage(report, 1).fname }}'s
                  {{ getPage(report, 5).sections[0].title }}</h3>
                <div class="doughnut_chart">
                  <doughnut-chart :series="motivatorsGraph(report).series"
                                  :plot-options="motivatorsGraph(report).plotOptions"></doughnut-chart>
                </div>
              </template>
            </template>
            <template v-if="getPage(report, 6)">
              <h3 class="report_title" v-if="getPage(report, 6).sections">{{ getPage(report, 1).fname }}'s
                {{ getPage(report, 6).sections[0].title }}</h3>
              <div class="report_text_sheet" v-if="getPage(report, 6).sections">
                <p v-if="getPage(report, 6).sections" v-for="item in getPage(report, 6).sections[0].paragraphs">
                  {{ item.paragraph }}
                </p>
              </div>
            </template>
            <template v-if="getPage(report, 7)">
              <h3 class="report_title" v-if="getPage(report, 7).sections">{{ getPage(report, 1).fname }}'s
                {{ getPage(report, 7).sections[0].title }}</h3>
              <div class="report_text_sheet" v-if="getPage(report, 7).sections">
                <table>
                  <thead>
                  <tr>
                    <th>Aptitude</th>
                    <th>Description</th>
                    <th colspan="2">Score(Out of 10)</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-for="item in getPage(report, 7).sections[0].parameters">
                    <td valign="top" data-label="Aptitude">{{ item.aptitude }}</td>
                    <td valign="top" data-label="Description">
                      <p>
                        {{ item.paragraphs[0].paragraph }}
                      </p>
                      <ul class="columnize_list" v-if="item.paragraphs[0].bullets">
                        <li v-for="bullet in item.paragraphs[0].bullets">{{ bullet.bullet }}</li>
                      </ul>
                    </td>
                    <td colspan="2" valign="top" data-label="Score(Out of 10)"><span class="td_score">{{
                        item.score
                      }}</span></td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </template>

            <template v-if="getPage(report, 8)">
              <template v-if="getPage(report, 8).sections">
                <h3 class="report_title">{{ getPage(report, 1).fname }}'s
                  {{ getPage(report, 8).sections[0].title }}</h3>
                <div class="report_graph">
                  <bar-graph :series="aptitudeGraph(report).series"
                             :plot-options="aptitudeGraph(report).plotOptions"></bar-graph>
                </div>
              </template>
            </template>
            <template v-if="getPage(report, 9)">
              <h3 class="report_title" v-if="getPage(report, 9).sections">{{ getPage(report, 1).fname }}'s
                {{ getPage(report, 9).sections[0].title }}</h3>
              <div class="report_text_sheet" v-if="getPage(report, 9).sections">
                <p v-for="item in getPage(report, 9).sections[0].paragraphs">
                  {{ item.paragraph }}
                </p>
                <table v-if="getPage(report, 9).sections">
                  <thead>
                  <tr>
                    <th>Theme</th>
                    <th>Code</th>
                    <th>Interests</th>
                    <th>Values</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr v-for="item in getPage(report, 9).sections[0].parameters">
                    <td valign="top" data-label="Theme">{{ item.theme }}</td>
                    <td valign="top" data-label="Code">{{ item.code }}</td>
                    <td valign="top" data-label="Interests">{{ item.interests }}</td>
                    <td valign="top" data-label="Values">{{ item.values }}</td>
                  </tr>
                  </tbody>
                </table>
              </div>
            </template>
            <template v-if="getPage(report, 10)">
              <template v-if="getPage(report, 10).sections">
                <h3 class="report_title">{{ getPage(report, 1).fname }}'s
                  {{ getPage(report, 10).sections[0].title }}</h3>
                <div class="report_graph">
                  <bar-graph :series="interestsGraph(report).series"
                             :plot-options="interestsGraph(report).plotOptions"></bar-graph>
                </div>
              </template>
            </template>
            <template v-if="getPage(report, 11)">
              <h3 class="report_title" v-if="getPage(report, 11).sections">{{ getPage(report, 1).fname }}'s
                {{ getPage(report, 11).sections[0].title }}</h3>
              <div class="report_text_sheet" v-if="getPage(report, 11).sections">
                <p v-for="item in getPage(report, 11).sections[0].paragraphs">
                  {{ item.paragraph }}
                </p>
              </div>
            </template>
            <template v-if="getPage(report, 12)">
              <h3 class="report_title">{{ getPage(report, 1).fname }}'s Personality Type</h3>
              <div class="report_text_sheet">
                <h4 v-if="getPage(report, 12).sections">{{ getPage(report, 12).sections[0].title }}</h4>
                <template v-if="getPage(report, 12).sections">
                  <template v-for="item in getPage(report, 12).sections[0].paragraphs">
                    <p>{{ item.paragraph }}</p>
                    <ul class="check_list" v-if="item.bullets">
                      <li v-for="bullet in item.bullets">{{ bullet.bullet }}</li>
                    </ul>
                  </template>
                </template>
              </div>
            </template>
          </div>
          <div class="report_footer">
            <div class="report_footer_copyright">Copyright © {{ new Date().getFullYear() }} Craydel Ltd.</div>
            <div class="footer_page_number">12</div>
          </div>
        </div>
      </div>
    </client-only>
  </div>
</template>

<script>
import BarGraph from "@/components/GraphComponents/BarGraph";
import PieChart from "@/components/GraphComponents/PieChart";
import DoughnutChart from "@/components/GraphComponents/DoughnutChart";
import {mapActions, mapState} from "vuex";
import {onLoad} from "~/helpers/onLoad";

export default {
  name: "ideal-report-printable",
  layout: "sampleReport",
  middleware: 'auth',
  components: {
    BarGraph,
    PieChart,
    DoughnutChart,
  },
  computed: {
    ...mapState('dashboard', {
      activeSlot: state => state.activeSlot,
      report: state => state.report,
    }),
  },
  data() {
    return {}
  },
  mounted() {
    if (!this.activeSlot) {
      this.$router.push('/my-packages')
    }

    //check if the page is loaded
    onLoad(() => {
      window.print()
      window.close()
    }, 2000)
  },
  methods: {
    getPage(report, pageNo) {
      if (report) {
        let reportObject = []

        //check if this is a tring or an object
        if (typeof report == "string") {
          reportObject = JSON.parse(report);
        } else {
          reportObject = report
        }
        if (reportObject[0]) {
          return reportObject[0].report.pages.find(page => page.sno === pageNo)
        } else {
          return []
        }
      } else {
        return []
      }

    },
    motivatorsGraph(report) {
      let chartData = this.getPage(report, 5).sections[0].chart.data;
      return {
        series: chartData.map(point => point.value),
        plotOptions: {
          labels: chartData.map(point => point.name),
          responsive: [
            {
              breakpoint: 600,
              options: {
                chart: {
                  width: 280
                },
                legend: {
                  position: "bottom"
                }
              }
            }
          ]
        }
      }
    },
    aptitudeGraph(report) {
      let chartData = this.getPage(report, 8).sections[0].chart.data;
      return {
        series: [{
          data: chartData.map(point => point.value)
        }],
        plotOptions: {
          xaxis: {
            categories: chartData.map(point => point.name),
          },

        }
      }
    },
    interestsGraph(report) {
      let chartData = this.getPage(report, 10).sections[0].chart.data;
      return {
        series: [{
          data: chartData.map(point => point.value)
        }],
        plotOptions: {
          xaxis: {
            categories: chartData.map(point => point.name),
          },
        }
      }
    },
  }
}
</script>

<style>
</style>
