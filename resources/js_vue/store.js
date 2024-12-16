import Vuex from 'vuex';
import axios from 'axios';
import VuexPersistence from 'vuex-persist';

const vuexLocal = new VuexPersistence({
    storage: window.localStorage,
  });

const store = new Vuex.Store({
    state: {
      user:null,
      token:'',
      tenant:null,
      authenticated:false,
      navbar_min:true,
      active:0,
    },
    mutations: {
      SET_USER(state,user){
        state.user = user;
      },
      SET_TOKEN(state,token){
        state.token = token;
      },
      SET_TENANT(state,tenant){
        state.tenant = tenant;
      },
      SET_AUTHENTICATION(state,data){
        state.user = data.user;
        state.token = data.token;
      },
      SET_AUTHENTICATED(state,auth){
        state.authenticated = auth;
      },
      SET_NAVBAR_MIN(state,toggle){
        state.navbar_min = toggle;
      },
      SET_ACTIVE(state,active){
        state.active = active;
      },

    },
    actions: {
        async signIn({ commit },user) {
            try {
              const res = await axios.post('/api/auth/login',user);
              const data = await res.data;
              commit('SET_USER', data.user);
              commit('SET_TOKEN', data.token);
              commit('SET_TENANT', data.tenant);
            } catch (error) {
              console.error('Error:', error);
            }
        },
        async logOut({ commit }) {
            axios.defaults.headers.common['Authorization'] = `Bearer ${this.state.token}`;
            try {
              const res = await axios.post('/api/auth/logout');
              const data = await res.data;
              commit('SET_USER', null);
              commit('SET_TOKEN', '');
              commit('SET_TENANT', null);
              commit('SET_AUTHENTICATED', false);
            } catch (error) {
              //console.error('Error:', error);
              commit('SET_USER', null);
              commit('SET_TOKEN', '');
              commit('SET_TENANT', null);
              commit('SET_AUTHENTICATED', false);
            }
        },
        async attempt({ commit, state }) {
            if (state.token) {
                commit('SET_TOKEN', state.token)
            }
            if (!state.token) {
                return
            }
            //header will be added by subcriber
            try {
                axios.defaults.headers.common['Authorization'] = `Bearer ${state.token}`;
                axios.defaults.headers.common['Access-Control-Allow-Origin'] = '*';
               let response = await axios.post('/api/auth/me');
                console.log(response)
                commit('SET_USER', response.data);
                //commit('SET_ENTREPRISE', response.data.entreprise);
                commit('SET_AUTHENTICATED', true);
            } catch (e) {
                commit('SET_TOKEN', '');
                commit('SET_USER', null);
                //commit('SET_ENTREPRISE', null);
                commit('SET_AUTHENTICATED', false);
            }

            return;

        },
    },
    getters: {
        getUser: (state) =>{
            return state.user;
        },

        getToken: (state) =>{
            return state.token;
        },

        getTenant:(state)=>{
            return state.token;
        },

        isAuthenticated:(state)=>{
            return state.user!=null;
        },


    },
    plugins: [vuexLocal.plugin],
});


export default store;
