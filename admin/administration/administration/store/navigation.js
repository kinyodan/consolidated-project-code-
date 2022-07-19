
export const state = () => ({
  state: {
    sidebarVisible: '',
    sidebarUnfoldable: false,
  },
})

export const mutations = {
    toggleSidebar(state) {
      state.sidebarVisible = !state.sidebarVisible
    },
    toggleUnfoldable(state) {
      state.sidebarUnfoldable = !state.sidebarUnfoldable
    },
    updateSidebarVisible(state, payload) {
      state.sidebarVisible = payload.value
    },
}

export const actions = {

}

export const getters = {

}

