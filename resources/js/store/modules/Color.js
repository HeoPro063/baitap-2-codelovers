import axios from 'axios'

const state = {
    listColor: [],
    activeModalAddColor: false,
    activeModalEditColor: false,
    statusColor: false,
}

const getters = {
    isListColor: state => state.listColor,
    isActiveModalAddColor: state => state.activeModalAddColor,
    isActiveModalEditColor: state => state.activeModalEditColor,
    isStatusColor: state => state.statusColor,
}

const actions = {
    async getColor({ commit }) {
        try {
            const res = await axios.get('api/color/index')
            commit('GET_COLOR', res.data)
        } catch (error) {
            console.log(error)
        }
    },
    async postColor({ commit }, data) {
        try {
            const res = await axios.post('api/color/create', data)
            commit('ADD_COLOR', res.data)
            commit('CHANGE_ACTICE_MODAL_ADD_COLOR')
            commit('CHANGE_STATUS_COLOR')
            var mess = {
                type: 'success',
                text: 'Thêm mới thành công',
            }
            commit('ADD_ALERT', mess)
        } catch (error) {
            commit('CHANGE_ACTICE_MODAL_ADD_COLOR')
            commit('CHANGE_STATUS_COLOR')
            var mess = {
                type: 'danger',
                text: 'Tên màu đã tồn tại',
            }
            commit('ADD_ALERT', mess)
        }
    },
    async editColor({ commit }, data) {
        try {
            var index = data.index
            const res = await axios.post('api/color/edit', data)
            var dataUpdate= res.data
            commit('EDIT_COLOR', {dataUpdate , index})
            commit('CHANGE_ACTICE_MODAL_EDIT_COLOR')
            commit('CHANGE_STATUS_COLOR')
            var mess = {
                type: 'success',
                text: 'Sửa thành công',
            }
            commit('ADD_ALERT', mess)
        } catch (error) {
            commit('CHANGE_ACTICE_MODAL_EDIT_COLOR')
            commit('CHANGE_STATUS_COLOR')
            var mess = {
                type: 'danger',
                text: 'Màu đã tồn tại',
            }
            commit('ADD_ALERT', mess)
        }
    },
    async deleteColor({ commit }, data) {
        try {
            var index = data.index
            var id = data.id
            await axios.get(`api/color/delete/${id}`)
            commit('DELETE_COLOR',  index)
            var mess = {
                type: 'success',
                text: 'Xóa màu thành công',
            }
            commit('ADD_ALERT', mess)
        } catch (error) {
        }
    },
}

const mutations = {
    GET_COLOR(state, data){
        state.listColor = data
    },
    CHANGE_ACTICE_MODAL_ADD_COLOR(state) {
        state.activeModalAddColor = !state.activeModalAddColor
    },
    CHANGE_ACTICE_MODAL_EDIT_COLOR(state) {
        state.activeModalEditColor = !state.activeModalEditColor
    },
    ADD_COLOR(state, data) {
        if(state.listColor == null){
            state.listColor = []
        }
        state.listColor.unshift(data)
    },
    EDIT_COLOR(state, {dataUpdate, index}) {
        state.listColor.splice(index, 1, dataUpdate)
    },
    CHANGE_STATUS_COLOR(state) {
        state.statusColor = !state.statusColor
    },
    DELETE_COLOR(state, index) {
        state.listColor.splice(index, 1)
    }
   
}

export default {
	state,
	getters,
	actions,
	mutations
}
