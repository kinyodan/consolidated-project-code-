import { mapGetters } from "vuex";

//courses state
export const state = () => ({
  token:false,
})

//state mutations
export const mutations = {
  SET_TOKEN(state, token) {
    state.token = token
  },
}

export const actions={
  async setToken({commit}, token) {
    commit('SET_TOKEN', {token})
  }
}


//getters
export const getters = {
  getToken: (state) => {
    return state.token
  }
}
