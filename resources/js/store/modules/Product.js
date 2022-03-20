import axios from 'axios'

const state = {
    listProduct: [],
    listCategories: [],
    activeModalAdd: false,
    activeModalUpdate: false,
    productPaginate: []
}

const getters = {
    isListProduct: state => state.listProduct,
    islistCategories: state => state.listCategories,
    isActiveModalAdd: state => state.activeModalAdd,
    isActiveModalUpdate: state => state.activeModalUpdate,
    isProductPaginate: state => state.productPaginate
}

const actions = {
    async getProducts({ commit }) {
        try {
            const res = await axios.get('api/category/product/index')
            commit('GET_PRODUCT', res.data[0])
        } catch (error) {
            console.log(error)
        }
    },
    async getCategories({ commit }) {
        try {
            const res = await axios.get('api/category/index')
            commit('GET_CATEGORIES', res.data[0])
        } catch (error) {
            console.log(error)
        }
    },
    async addProduct({ commit }, data) {
        try {
            const res = await axios.post('api/category/product/create', data)
            commit('ADD_PRODUCT', res.data[0])
            commit('CHANGE_ACTICE_MODAL_ADD')
        } catch (error) {
            console.log(error)
        }
    },
    async deleteProduct({ commit }, data) {
        try {
            const id = data.idProduct
            const index = data.index
            await axios.get(`api/category/product/delete/${id}`)
            commit('DELETE_PRODUCT', index)
        } catch (error) {
            console.log(error)
        }
    },
    async editProduct({ commit }, data) {
        try {

            const formData = data.formData
            const index = data.index
            const res = await axios.post(`api/category/product/edit`, formData)
            const dataUpdate = res.data[0]
            commit('UPDATE_PRODUCT', {dataUpdate, index})
            commit('CHANGE_ACTICE_MODAL_UPDATE')
        } catch (error) {
            console.log(error)
        }
    },
    async getPaginateProduct({commit}, dataGet){
        try {

            const page_url = dataGet.url_get
            const dataSearch = {
                search: dataGet.dataSeach
            } 
            const res = await axios.post(page_url, dataSearch)
            let dataPaginate = {
                current_page: res.data.current_page,
                last_page: res.data.last_page,
                links: res.data.links,
                next_page_url: res.data.next_page_url,
                prev_page_url: res.data.prev_page_url,
            }
            commit('GET_PRODUCT', res.data.data)
            commit('PUSH_PAGINATE_PRODUCT', dataPaginate)
        } catch (error) {
            console.log(error)
        }
    }
}

const mutations = {
    GET_PRODUCT(state, data){
        state.listProduct = data
    },
    GET_CATEGORIES(state, data){
        state.listCategories = data
    }, 
    ADD_PRODUCT(state, data){
        state.listProduct.unshift(data)
    },
    CHANGE_ACTICE_MODAL_ADD(state){
        state.activeModalAdd = !state.activeModalAdd
    },
    DELETE_PRODUCT(state, data){
        state.listProduct.splice(data, 1)
    },
    CHANGE_ACTICE_MODAL_UPDATE(state){
        state.activeModalUpdate = !state.activeModalUpdate
    },
    UPDATE_PRODUCT(state,  {dataUpdate, index}){
        state.listProduct.splice(index, 1, dataUpdate)
    },
    PUSH_PAGINATE_PRODUCT(state, data){
        state.productPaginate = data
    }
}

export default {
	state,
	getters,
	actions,
	mutations
}
