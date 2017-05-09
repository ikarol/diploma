import VueRouter from 'vue-router';

var routes = [
    {
        path: '/prof-task-list',
        component: require('./components/ProfessorDiplomasTable')
    },
    {
        path: '/stud-task-list',
        component: require('./components/StudentDiplomasTable')
    }
];

export default new VueRouter({
    routes: routes,
    linkActiveClass : 'active'
})
