import axios from 'axios'

const state = {
    listSize: [],
    activeModalAddSize: false,
    activeModalEditSize: false,
    statusSize: false,
}

const getters = {
    isListSize: state => state.listSize,
    isActiveModalAddSize: state => state.activeModalAddSize,
    isActiveModalEditSize: state => state.activeModalEditSize,
    isStatusSize: state => state.statusSize,
}

const actions = {
    async getSize({ commit }) {
        try {
            const res = await axios.get('api/size/index')
            commit('GET_SIZE', res.data)
        } catch (error) {
            console.log(error)
        }
    },
    async postSize({ commit }, data) {
        try {
            const res = await axios.post('api/size/create', data)
            commit('ADD_SIZE', res.data)
            commit('CHANGE_ACTICE_MODAL_ADD_SIZE')
            commit('CHANGE_STATUS_SIZE')
        } catch (error) {
            commit('CHANGE_ACTICE_MODAL_ADD_SIZE')
            commit('CHANGE_STATUS_SIZE')
        }
    },
    async editSize({ commit }, data) {
        try {
            var index = data.index
            const res = await axios.post('api/size/edit', data)
            var dataUpdate= res.data
            commit('EDIT_SIZE', {dataUpdate , index})
            commit('CHANGE_ACTICE_MODAL_EDIT_SIZE')
            commit('CHANGE_STATUS_SIZE')
        } catch (error) {
            commit('CHANGE_ACTICE_MODAL_EDIT_SIZE')
            commit('CHANGE_STATUS_SIZE')
        }
    },
    async deleteSize({ commit }, data) {
        try {
            var index = data.index
            var id = data.id
            await axios.get(`api/size/delete/${id}`)
            commit('DELETE_SIZE',  index)
        } catch (error) {
        }
    },
}

const mutations = {
    GET_SIZE(state, data){
        state.listSize = data
    },
    CHANGE_ACTICE_MODAL_ADD_SIZE(state) {
        state.activeModalAddSize = !state.activeModalAddSize
    },
    CHANGE_ACTICE_MODAL_EDIT_SIZE(state) {
        state.activeModalEditSize = !state.activeModalEditSize
    },
    ADD_SIZE(state, data) {
        if(state.listSize == null){
            state.listSize = []
        }
        state.listSize.unshift(data)
    },
    EDIT_SIZE(state, {dataUpdate, index}) {
        state.listSize.splice(index, 1, dataUpdate)
    },
    CHANGE_STATUS_SIZE(state) {
        state.statusSize = !state.statusSize
    },
    DELETE_SIZE(state, index) {
        state.listSize.splice(index, 1)
    }
   
}

export default {
	state,
	getters,
	actions,
	mutations
}
