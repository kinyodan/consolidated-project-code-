export const state = () => ({
  filterCountry: ""
});

export const mutations ={
  SET_FILTER_COUNTRY(state, countryCode){
    state.filterCountry = countryCode
  }
}
export const actions ={
  setFilterCountry({commit}, {countryCode}){
    commit('SET_FILTER_COUNTRY', countryCode)
  }
}
