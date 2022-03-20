import axios from 'axios'

const state = {
    listCategory: [],
    paginate: [],
    showAlert: false,
}

const getters = {
    isListCategories: state => state.listCategory,
    isAlert: state => state.showAlert,
    isPaginate: state => state.paginate    
}

const actions = {
    // async getCategories({ commit }) {
    //     try {
    //         const res = await axios.get('api/category/index')
    //         commit('GET_CATTEGORIES', res.data)
    //     } catch (error) {
    //         console.log(error)
    //     }
    // },
    async addCategory({ commit }, data) {
        try {
            const res = await axios.post('api/category/create', data)
            commit('ADD_CATEGORY', res.data)
        } catch (error) {
            console.log(error)
        }
    },
    async editCategory({ commit }, data) {
        try {
            const res = await axios.post('api/category/edit', data)
            const dataUpdate = res.data
            const dataIndex = data.index
            commit('UPDATE_CATEGORIES', {dataUpdate, dataIndex})
        } catch (error) {
            console.log(error)
        }
    },
    async deleteCategory({ commit }, data) {
        try {
            const dataIndex = data.index
            const dataId =  data.id
            await axios.get(`api/category/delete/${dataId}`)
            commit('DELETE_CATEGORY', dataIndex)
        } catch (error) {
            console.log(error)
        }
    },
    async getPaginateCategory({commit}, dataGet){
        try {

            const page_url = dataGet.url_get
            const dataSearch = {
                search: dataGet.dataSeach
            } 
            const res = await axios.post(page_url, dataSearch)
            let dataPaginate = {
                current_page: res.data.current_page,
                last_page: res.data.last_page,
                next_page_url: res.data.next_page_url,
                prev_page_url: res.data.prev_page_url,
            }
            commit('GET_CATTEGORIES', res.data.data)
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
        state.listCategory.unshift(data)
    },
    GET_ALERT(state, status){
        state.showAlert = status;
    },
    UPDATE_CATEGORIES(state,  {dataUpdate, dataIndex}){
        state.listCategory.splice(dataIndex, 1, dataUpdate)
    },
    DELETE_CATEGORY(state, dataIndex){
        state.listCategory.splice(dataIndex, 1)
    },
    PUSH_PAGINATE(state, data){
        state.paginate = data
    }
}

export default {
	state,
	getters,
	actions,
	mutations
}
