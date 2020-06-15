import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        currentUser: {
            name: '游客'
        } // 当前用户
    },
    mutations: {
        setCurrentUser(state, userData) {
            state.currentUser = userData
        }
    },
    actions: {

    },
    getters: {
        greeting(state) {
            let d = new Date();
            let time = d.getHours();

            if (time > 6 && time < 12) {
                return '早上好, ' + state.currentUser.name
            } else if (time >= 12 && time < 19) {
                return '下午好, ' + state.currentUser.name
            } else {
                return '晚上好, ' + state.currentUser.name
            }
        }
    }
})