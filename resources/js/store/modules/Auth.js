import axios from 'axios'

const state = {
	auth: {
		isAuthenticated: ''
	},
    user: {}
}

const getters = {
	isAuthenticated: state => state.auth.isAuthenticated,
    isUser: state => state.user 
}

const actions = {
    async login({ commit }, data) {
        try {
            const res = await axios.post('api/login', data)
            commit('GET_USER', res.data)
            commit('TOGGLE_AUTH', true)
        } catch (error) {
            console.log(error)
        }
    },
    async athenticated({ commit }){
        try{
            await axios.get('api/athenticated')
            commit('TOGGLE_AUTH', true)
        }catch (error){
            commit('TOGGLE_AUTH', false)
        }
    },
    async getUser({ commit }){
        try{
            const res = await axios.get('api/user')
            commit('GET_USER', res.data)
        }catch (error){
            commit('TOGGLE_AUTH', false)
        }
    },
    async logout({ commit }) {
        try {
            await axios.get('api/logout')
            commit('TOGGLE_AUTH', false)
        } catch (error) {
            console.log(error)
        }
    },
}

const mutations = {
    TOGGLE_AUTH(state, status) {
		state.auth.isAuthenticated = status
	},
    GET_USER(state, data){
        state.user = data
    }
}

export default {
	state,
	getters,
	actions,
	mutations
}
