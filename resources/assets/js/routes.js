import VueRouter from 'vue-router';

var routes = [
    {
        path: '/prof-diploma-list',
        component: require('./components/ProfessorDiplomasTable')
    },
    {
        path: '/prof-diploma-requests',
        component: require('./components/ProfessorDiplomasRequests')
    },
    {
        path: '/stud-task-list',
        component: require('./components/StudentDiplomasTable')
    },
    {
        path: '/stud-requests',
        component: require('./components/StudentDiplomasRequests')
    }
];

export default new VueRouter({
    routes: routes,
    linkActiveClass : 'active'
})
