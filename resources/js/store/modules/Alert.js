const state = {
    listAlert: [],
}

const getters = {
    isListAlert: state => state.listAlert,
}

const actions = {
  
}

const mutations = {
    ADD_ALERT(state, data){
        if(state.listAlert == null) {
            state.listAlert = []
        }
        state.listAlert.push({
            ...data,
            id: Date.now().toString(36)
        })
    },
    REMOVE_ALERT(state, data){
        state.listAlert = state.listAlert.filter(item => {
            return item.id != data.id
        })
    },
}

export default {
	state,
	getters,
	actions,
	mutations
}
