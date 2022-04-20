import axios from 'axios'

const state = {
    listProduct: [],
    listCategories: [],
    activeModalAdd: false,
    activeModalUpdate: false,
    productPaginate: [],
    dataProductDetail: [],
    dataProductRalate: [],
    status: false
}

const getters = {
    isListProduct: state => state.listProduct,
    islistCategories: state => state.listCategories,
    isActiveModalAdd: state => state.activeModalAdd,
    isActiveModalUpdate: state => state.activeModalUpdate,
    isProductPaginate: state => state.productPaginate,
    isDataProductDetail: state => state.dataProductDetail,
    isDataProductRalate: state => state.dataProductRalate,
    isStatus: state => state.status
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
            commit('CHANGE_STATUS')
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
            console.log(data)
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
            commit('CHANGE_STATUS')
            commit('UPDATE_PRODUCT', {dataUpdate, index})
            commit('CHANGE_ACTICE_MODAL_UPDATE')
        } catch (error) {
            console.log(error)
        }
    },
    async getPaginateProduct({commit}, dataGet){
        try {
            const page_url = dataGet.url_get
            var dataSearch = null
            if(dataGet.action === 1){
                dataSearch = {
                    search: dataGet.dataSeach
                } 
            }else if(dataGet.action === 2){
                dataSearch = {
                    colorOrSize: dataGet.dataSeach
                } 
            }else if(dataGet.action === 3){
                dataSearch = {
                    idProduct: dataGet.dataSeach
                } 
            }else if(dataGet.action === 4){
                dataSearch = {
                    idCategory: dataGet.dataSeach
                } 
            }
            const res = await axios.post(page_url, dataSearch)
            let dataPaginate = {
                limit: res.data.limit,
                page: Number(res.data.page),
                total_page: res.data.total_page,
            }
            commit('GET_PRODUCT', res.data.data_hits)
            commit('PUSH_PAGINATE_PRODUCT', dataPaginate)
        } catch (error) {
            console.log(error)
        }
    },
    async getDetailProduct({commit}, data){
        try {
            const res = await axios.get(`api/category/product/show/${data}`)
            const dataDetail = res.data[0].product_detail;
            const dataRalate = res.data[0].product_ralate;
            commit('ADD_PRODUCT_DETAIL', dataDetail)
            commit('ADD_PRODUCT_RALATE', dataRalate)
        } catch (error) {
            console.log(error)
        }
    },
}

const mutations = {
    GET_PRODUCT(state, data){
        state.listProduct = data
    },
    GET_CATEGORIES(state, data){
        state.listCategories = data
    }, 
    ADD_PRODUCT(state, data){
        if(state.listProduct == null){
            state.listProduct = []
        }
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
    },
    ADD_PRODUCT_DETAIL(state, data){
        state.dataProductDetail = data
    },
    ADD_PRODUCT_RALATE(state, data){
        state.dataProductRalate = data
    },
    RESET_DATA_PRODUCT_DETAIL(state){
        state.dataProductRalate = []
        state.dataProductDetail = []
    },
    CHANGE_STATUS(state) {
        state.status = !state.status
    }
}

export default {
	state,
	getters,
	actions,
	mutations
}
