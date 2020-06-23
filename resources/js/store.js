import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
    state: {
        currentUser: {
            name: '游客'
        }, // 当前用户
        tabs: [], // 标签页列表
        activatedRequest: true, // 是否需要在激活时候重新发送请求
    },
    mutations: {
        setCurrentUser(state, userData) {
            state.currentUser = userData
        },
        pushTabs(state, data) {
            let has = false;
            state.tabs.forEach((value, index) => {
                if (value['title'] == data['title']) {
                    state.tabs[index] = data;
                    return has = true;
                }
            });
            if (!has) state.tabs.push(data);
        },
        setTabs(state, data) {
            state.tabs = data;
        },
        removeTab(state, data) {
            let test = state.tabs
            test.forEach((value, index) => {
                if (value.path == data.path)
                    state.tabs.splice(index, 1)
            })
        },
        setActivatedRequest(state, data) {
            state.activatedRequest = data
        }
    },
    actions: {
        removeTab({ commit }, date) {
            return new Promise((resolve, reject) => {
                try {
                    commit('removeTab', date);
                    resolve(date);
                } catch (error) {
                    reject(error);
                }
            })


        },
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