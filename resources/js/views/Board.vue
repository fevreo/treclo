<template>
    <div class="container">
        <div class="row justify-content-center">
            <div element="div" class="col-md-12">
                <transition-group class="row">
                    <div class="col-md-4" v-for="(element, index) in categories" :key="element.id">
                        <div class="rounded-top">
                            <div class="card-header bg-light">
                                <h4 class="card-title">{{ element.name }}</h4>
                            </div>
                            <div class="card-body card-body-dark">
                                <draggable :options="dragOptions" element="div" @end="changeOrder"
                                    :v-model="element.tasks">
                                    <transition-group :id="element.id">
                                        <div v-for="task in element.tasks" :key="
                                            task.category_id +
                                            ',' +
                                            task.order
                                        " class="transit-1 my-3" :id="task.id">
                                            <div class="small-card bg-light d-flex justify-content-between">
                                                <textarea v-if="task === editingTask" class="text-input" @keyup.enter="
                                                    endEditing(task)
                                                " @blur="endEditing(task)" v-model="task.name"></textarea>
                                                <label for="checkbox" v-if="task !== editingTask"
                                                    @dblclick="editTask(task)">{{ task.name }}</label>
                                                <button type="button" class="btn btn-outline-danger btn-sm"
                                                    @click="deleteTask(task)">
                                                    del
                                                </button>
                                            </div>
                                        </div>
                                        <div class="dummy" :key="element.id">DummyData</div>
                                    </transition-group>
                                </draggable>
                                <div class="small-card bg-light xt-15">
                                    <h5 class="text-center" @click="addNew(index)">
                                        Add new card
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </transition-group>
            </div>
        </div>
    </div>
</template>

<style scoped>
.transit-1 {
    transition: all 1s;
}

.small-card {
    padding: 1rem;
    margin-bottom: 5px;
    border-radius: 0.25rem;
}

.card-body-dark {
    background-color: #ccc;
}

.dummy {
    opacity: 0;
    margin: 0;
}

textarea {
    overflow: visible;
    outline: 1px dashed black;
    border: 0;
    padding: 6px 0 2px 8px;
    width: 100%;
    height: 100%;
    resize: none;
}
</style>

<script>
import draggable from "vuedraggable";
export default {
    components: {
        draggable,
    },
    data() {
        return {
            user: [],
            categories: [],
            editingTask: null,
        };
    },
    methods: {
        addNew(id) {
            let user_id = this.user;
            let name = "Task";
            let category_id = this.categories[id].id;
            let order = this.categories[id].tasks.length;

            axios
                .post("api/task", { user_id, name, order, category_id })
                .then((response) => {
                    this.categories[id].tasks.push(response.data.data);
                });
        },
        loadTasks() {
            this.categories.map((category) => {
                axios
                    .get(`api/category/${category.id}/tasks`)
                    .then((response) => {
                        category.tasks = response.data;
                    });
            });
        },
        changeOrder(data) {
            axios
                .patch("api/task", { tasks: this.categories });
        },
        endEditing(task) {
            this.editingTask = null;
            axios.patch(`api/task/${task.id}`, { name: task.name });
        },
        editTask(task) {
            this.editingTask = task;
        },
        deleteTask(task) {
            axios.delete(`api/task/${task.id}`);
            this.loadTasks();
        }
    },
    mounted() {
        let token = localStorage.getItem("jwt");
        this.user = localStorage.getItem("user_id");

        axios.defaults.headers.common["Content-Type"] = "application/json";
        axios.defaults.headers.common["Authorization"] = "Bearer " + token;

        axios.get("api/category").then((response) => {
            response.data.forEach((data) => {
                this.categories.push({
                    id: data.id,
                    name: data.name,
                    tasks: [],
                });
            });

            this.loadTasks();
        });
    },
    computed: {
        dragOptions() {
            return {
                animation: 0.1,
                group: "description",
                ghostClass: "ghost",
            };
        },
    },
    beforeRouteEnter(to, from, next) {
        if (!localStorage.getItem("jwt")) {
            return next("/login");
        }

        next();
    },
};
</script>