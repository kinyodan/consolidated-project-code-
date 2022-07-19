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
                    <td colspan="2" valign="top" data-label="Score(Out of 10)"><span class="td_score">{{ item.score }}</span></td>
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

export default {
  name: "ideal-report",
  layout:"sampleReport",
  components: {
    BarGraph,
    PieChart,
    DoughnutChart,
  },
  computed: {
  },
  data() {
    return {
      report:[{"test_id": "82911","pdf_url": "https://s3-ap-southeast-1.amazonaws.com/fal-careerguide/id-la/82911.pdf","report": {"pages": [{"sno": 1,"fname": "Jane","lname": "Doe","email": "info@craydel.com","mobile": "+254783125125","date": "2021-06-03","sections": [{"title": "How will Psychometric Assessment Report Help?","bullets": [{"bullet": "To identify and explore career in order to get started in career search"}, {"bullet": "To identify strengths and potential weaknesses for the career search"}, {"bullet": "To plan career goals and action steps"}]}]}, {"sno": 2,"sections": [{"title": "Suggested Careers","sectors": [{"name": "Defense & Military","description": "Protective services and homeland security, including professional and technical support services of Army, Navy and Airforce including Paramilitary Force.","sector_code": "defense-military","job_roles": [{"option": "Air Force Related Careers","job_role_code": "air-force-related-careers"},{"option": "Police Service - Central Police Reserve Force","job_role_code": "police-service-central-police-reserve-force"},{"option": "Police Service- Border Security Force","job_role_code": "police-service-border-security-force"},{"option": "Army Related Careers","job_role_code": "army-related-careers"},{"option": "Navy Related Careers","job_role_code": "navy-related-careers"}]}, {"name": "Tourism & Hospitality","description": "Hospitality & Tourism encompasses the management, marketing and operations of restaurants and other foodservices, lodging, attractions, recreation events and travel related services.","sector_code": "tourism-hospitality","job_roles": [{"option": "Tourist Guide/Manager","job_role_code": "tourist-guide-manager"}, {"option": "Interpreters & Translators","job_role_code": "interpreters-translators"}, {"option": "Hotel Managers","job_role_code": "hotel-managers"}, {"option": "Video Jockey","job_role_code": "video-jockey"}, {"option": "Radio Jockey","job_role_code": "radio-jockey"}, {"option": "Cabin Crew","job_role_code": "cabin-crew"}, {"option": "Chef","job_role_code": "chef"}]}, {"name": "Designing & Art","description": "Designing, producing, exhibiting, performing, writing and publishing multi-media content, including visual and performing arts and design, journalism, and entertainment services.","sector_code": "designing-art","job_roles": [{"option": "Radio Jockey","job_role_code": "radio-jockey"}, {"option": "Video Jockey","job_role_code": "video-jockey"}, {"option": "Writer","job_role_code": "writer"}, {"option": "Beautician","job_role_code": "beautician"}, {"option": "Photographer","job_role_code": "photographer"}, {"option": "Gemologist","job_role_code": "gemologist"}, {"option": "Illustrator","job_role_code": "illustrator"}]}]}]}, {"sno": 3,"sections": [{"title": "Career Motivators","paragraphs": [{"paragraph": "Career Motivators, in the simplest term, are factors that encourage one to perform better at work. It could be anything- Money, Respect, Creativity, Challenge, and Recognition."}, {"paragraph": "Career Motivators may not be same for every person. To one person, money could be of higher priority, to another recognition. Hence, it is very important that one chooses his/her career that has areas of motivational factor. Career Motivators are an important element in choosing a career else one's career after a while may seem boring and uninteresting.","bullets": [{"bullet": "To a businessman, profits (money) could be the motivational factor whereas to a politician, seeing the nation's development is the motivational factor."}, {"bullet": "Being in social work career will get you lots of respect from society when compared to being engineer. But being a social worker would mean travel and less comfortable life as one requires to fight and help downtrodden."}, {"bullet": "Being an engineer would mean more stability but would not bag respect as that of social worker. So if you want your career to be more comfortable and predictable then being an engineer would be a better option than being a social worker."}]}]}]}, {"sno": 4,"sections": [{"title": "Top Motivators","motivators": [{"name": "FREEDOM","description": "A suitable job for you is the one that will not bind you in terms of time schedule and monitoring. Yes, it may sound a silly thing to ask for. But not all take their freedom for granted. A few of us want freedom, along with a paycheck, as an important criteria in choosing the job."}, {"name": "INTEREST","description": "A job that is of your interest is ideal for you as it will keep you at it, irrespective of hurdles and troubles. This probably is the most basic and natural career motivator. If one likes a job, one will do it well; irrespective of hurdles."}, {"name": "LEISURE","description": "A job that allows you to have time enough for having a personal life is what you want. Something like a teacher's job which is not very hectic and does not occupy all day. While a few may argue, work has no concept like leisure. But contradicting that we say,it does. Many jobs require less working hours, which eventually leaves them with leisure hours!"}]}]}, {"sno": 5,"sections": [{"title": "Motivators Graph","chart": {"type": "pie","data": [{"name": "Challenge","value": 2}, {"name": "Interest","value": 3}, {"name": "Freedom","value": 3}, {"name": "Leisure","value": 3}, {"name": "Money","value": 2}, {"name": "Service","value": 3}, {"name": "Stability","value": 3}, {"name": "Variety","value": 3}, {"name": "Leadership","value": 2}, {"name": "Respect","value": 1}]}}]}, {"sno": 6,"sections": [{"title": "Aptitude Report","paragraphs": [{"paragraph": "It may be true that with hard work and determination, one can perform well in any field. However, having an aptitude for that field can make things much easier. But what is aptitude? In the simplest of terms, Aptitude is nothing but the natural ease of learning something. And if that is there, a career journey can become very easy. Hence, it becomes essential that you know your aptitude and choose a career."}, {"paragraph": "For example, if your verbal communication skills are at VERY GOOD level, which means have high aptitude in that. However, your writing skills or your body language may be at average level or even below average. In such case you need to focus more on the low aptitude areas. This is the advantage of specifically identifying the problem areas. It reduces the amount of effort you need to put on your training for the improvements."}, {"paragraph": "Please find your associated scores for different Aptitude parameters"}]}]}, {"sno": 7,"sections": [{"title": "Aptitude Parameters","parameters": [{"aptitude": "Verbal Aptitude","paragraphs": [{ "paragraph": "It measures the ability of a person to use words in as effective manner as possible. People with this aptitude are good in expression and can easily build good vocabulary.", "bullets": [{ "bullet": "Good Communications" }, { "bullet": "Has a good vocabulary" }, { "bullet": "Can Easily Express Ideas" }, { "bullet": "Likes reading/writing" }] }],"score": 8}, {"aptitude": "Numerical Aptitude","paragraphs": [{ "paragraph": "It measures the ability of a person to reason with numbers and to deal intelligently with quantitative measures. People with this aptitude are good in understanding, exploring and manipulating systems.", "bullets": [{ "bullet": "Scientific & Logical thinking" }, { "bullet": "Comfortable with numbers" }, { "bullet": "Can work alone for long" }, { "bullet": "Good quantitative analysis" }] }],"score": 6}, {"aptitude": "Critical Dissection","paragraphs": [{ "paragraph": "It measures the ability of a person to analyze situations in best possible way. People with this aptitude are also characterized with easy convincing and influencing abilities.", "bullets": [{ "bullet": "Logical and convincing" }, { "bullet": "Acute sense of analysis" }, { "bullet": "Technical blend of mind" }] }],"score": 3}, {"aptitude": "Acuteness Aptitude","paragraphs": [{ "paragraph": "It measures how accurately and precisely one can do the given task. Can follow instructions quite effectively. They can attend a task or work in detail very easily.", "bullets": [{ "bullet": "Good Speed and Accuracy" }, { "bullet": "Good Concentration" }, { "bullet": "Good Observation Power" }, { "bullet": "Can do repetitive tasks" }] }],"score": 2}, {"aptitude": "Spatial Aptitude","paragraphs": [{ "paragraph": "It measures the ability to visualize a 3D object from a 2D patterns. It suggests having great sense of form and symmetry and being good at imagination.", "bullets": [{ "bullet": "High degree of creativity" }, { "bullet": "Sound observation power" }, { "bullet": "Technical blend of mind" }, { "bullet": "Good at imagination" }] }],"score": 2}]}]}, {"sno": 8,"sections": [{"title": "Aptitude Report Graph","chart": {"type": "bar","axis_y_max": 9,"axis_y_min": 0,"data": [{"name": "Critical Dissection","value":3}, {"name": "Numerical","value":6}, {"name": "Verbal","value":8}, {"name": "Acuteness","value":2}, {"name": "Spatial","value":2}]}}]}, {"sno": 9,"sections": [{"title": "Top Interest","paragraphs": [{"paragraph": "Take a moment to ask, What do I enjoy?"}, {"paragraph": "Interests are those activities you do for fun or enjoyment, including what you like to read, the TV shows and movies you like to watch, events you like to attend, and the kind of people you admire and like to be with."}, {"paragraph": "Interest is important in choosing a career or occupation because interest keeps you motivated and engaged in your work. Since work takes up most of your time during a day and week, if you have no interest in what you are doing, you will most likely become unhappy and unproductive."}],"parameters": [{"theme": "R","code": "Realistic","interests": "Mechanical ingenuity and dexterity, physical coordination, working outdoors","values": "Tradition, practicality, common sense"}, {"theme": "S","code": "Social","interests": "People skills, teamwork, helping community, verbal ability, listening, showing understanding","values": "Cooperation, generosity, service to others"}, {"theme": "A","code": "Artistic","interests": "Self-expressions, art, appreciation, communication, culture","values": "Beauty, originality, independence, imagination"}]}]}, {"sno": 10,"sections": [{"title": "Interest Report Graph","chart": {"type": "bar","axis_y_max": 12,"axis_y_min": 0,"data": [{"name": "Social","value":11}, {"name": "Enterprising","value":7}, {"name": "Investigative","value":8}, {"name": "Realistic","value":12}, {"name": "Artistic","value":9}, {"name": "Conventional","value":8}]}}]}, {"sno": 11,"sections": [{"title": "Personality Report","paragraphs": [{"paragraph": "We all have distinguished personality traits, like a few of us are more analytical in nature, while others may have an artistic bent of mind. One may be good at managing numbers, other may excel at handling people. These qualities in us can have a great impact on the career we choose. Hence, keeping in mind one's personality trait will help in making one's career easy. These inbuilt skills will help shape the career smoothly and the chosen job will not be a big task."}, {"paragraph": "For example, someone who is introvert by nature will find it quite difficult to be in a sales job as the work requires a go-getter attitude. Yet another instance is someone without the sense of language appreciation will not be suitable for an editorial job."}]}]}, {"sno": 12,"sections": [{"title": "You are Imaginative + Spontaneous + Assertive + Gregarious","paragraphs": [{"paragraph": "You tend to be a  experimenter. You are considerate  , thoughtful , inspired , spontaneous , insightful , caring , active , idealistic , involved , intense."}, {"paragraph": "Your Plus Points are:","bullets": [{"bullet": "You are wise"},{"bullet": "You are visionary"},{"bullet": "Your loving attitude"}]}, {"paragraph": "Your Improvement Points are:","bullets": [{"bullet": "Your impulsiveness nature"},{"bullet": "May 'tread on toes'"},{"bullet": "Demanding character"}]}]}]}]}}]
    }
  },
  methods: {
    printReport(){
        window.print();
    },
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
