import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        currentUser: {
            name: '游客'
        }, // 当前用户
        tabs: [], // 标签页列表
    },
    mutations: {
        setCurrentUser(state, userData) {
            state.currentUser = userData
        },
        pushTabs(state, data) {
            let has = false;
            state.tabs.forEach((value) => {
                if (value['path'] == data['path']) return has = true;
            });
            if (!has) state.tabs.push(data);
        },
        setTabs(state, data) {
            state.tabs = data;
        },
        removeTab(state, data) {
            state.tabs.splice(state.tabs.indexOf(data), 1);
        },
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