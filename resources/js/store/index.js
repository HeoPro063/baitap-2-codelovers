import Vue from 'vue'
import Vuex from 'vuex'

// Import modules
import Auth from './modules/Auth'
import Category from './modules/Category'
import Product from './modules/Product'
import Color from './modules/Color'
import Size from './modules/Size'
import Alert from './modules/Alert'

Vue.use(Vuex)

const storeData = {
	modules: {
        Auth,
		Category,
		Product,
		Color,
		Size,
		Alert
	}
}

const store = new Vuex.Store(storeData)



export default store
