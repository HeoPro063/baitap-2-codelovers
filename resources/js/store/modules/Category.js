import axios from 'axios'

const state = {
    listCategory: [],
    paginate: [],
    statusCategory: false,
    activeModalAddCategory: false,
    activeModalEditCategory: false
}

const getters = {
    isListCategories: state => state.listCategory,
    isPaginate: state => state.paginate, 
    isStatusCategory: state => state.statusCategory,
    isActiveModalAddCategory: state => state.activeModalAddCategory, 
    isActiveModalEditCategory: state => state.activeModalEditCategory 
}

const actions = {
    async addCategory({ commit }, data) {
        try {
            const res = await axios.post('api/category/create', data)
            commit('ADD_CATEGORY', res.data)
            commit('CHANGE_ACTICE_MODAL_ADD_CATEGORY')
            commit('CHANGE_STATUS_CATEGORY')
            var mess = {
                type: 'success',
                text: 'Thêm mới thành công',
            }
            commit('ADD_ALERT', mess)
        } catch (error) {
            commit('CHANGE_ACTICE_MODAL_ADD_CATEGORY')
            commit('CHANGE_STATUS_CATEGORY')
            var mess = {
                type: 'danger',
                text: 'Tên danh mục đã tồn tại',
            }
            commit('ADD_ALERT', mess)
        }
    },
    async editCategory({ commit }, data) {
        try {
            const res = await axios.post('api/category/edit', data)
            const dataUpdate = res.data
            const dataIndex = data.index
            commit('UPDATE_CATEGORIES', {dataUpdate, dataIndex})
            commit('CHANGE_ACTICE_MODAL_EDIT_CATEGORY')
            commit('CHANGE_STATUS_CATEGORY')
            var mess = {
                type: 'success',
                text: 'Sửa thành công',
            }
            commit('ADD_ALERT', mess)
        } catch (error) {
            commit('CHANGE_ACTICE_MODAL_EDIT_CATEGORY')
            commit('CHANGE_STATUS_CATEGORY')
            var mess = {
                type: 'danger',
                text: 'Tên danh mục đã tồn tại',
            }
            commit('ADD_ALERT', mess)
        }
    },
    async deleteCategory({ commit }, data) {
        try {
            const dataIndex = data.index
            const dataId =  data.id
            await axios.get(`api/category/delete/${dataId}`)
            commit('DELETE_CATEGORY', dataIndex)
            var mess = {
                type: 'success',
                text: 'Xóa danh mục thành công',
            }
            commit('ADD_ALERT', mess)
        } catch (error) {
            console.log(error)
        }
    },
    async getPaginateCategory({commit}, dataGet){
        try {
            let dataRequest = {
                search : dataGet.dataSearch
            }
            let url = dataGet.url_get;
            const res = await axios.post(url, dataRequest)
            let dataPaginate = {
                limit: res.data.limit,
                page: Number(res.data.page),
                total_page: res.data.total_page,
            }
            commit('GET_CATTEGORIES', res.data.data_hits)
            commit('PUSH_PAGINATE', dataPaginate)
        } catch (error) {
            console.log(error)
        }
    }
}

const mutations = {
    GET_CATTEGORIES(state, data){
        state.listCategory = data
    },
    ADD_CATEGORY(state, data){
        if(state.listCategory == null){
            state.listCategory = []
        }
        state.listCategory.unshift(data)
    },
    UPDATE_CATEGORIES(state,  {dataUpdate, dataIndex}){
        state.listCategory.splice(dataIndex, 1, dataUpdate)
    },
    DELETE_CATEGORY(state, dataIndex){
        state.listCategory.splice(dataIndex, 1)
    },
    PUSH_PAGINATE(state, data){
        state.paginate = data
    },
    CHANGE_STATUS_CATEGORY(state) {
        state.statusCategory = !state.statusCategory
    },
    CHANGE_ACTICE_MODAL_ADD_CATEGORY(state) {
        state.activeModalAddCategory = !state.activeModalAddCategory
    },
    CHANGE_ACTICE_MODAL_EDIT_CATEGORY(state) {
        state.activeModalEditCategory = !state.activeModalEditCategory
    }
}

export default {
	state,
	getters,
	actions,
	mutations
}
