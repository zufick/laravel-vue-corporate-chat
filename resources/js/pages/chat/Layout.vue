<template>
    <v-app id="inspire">
        <v-app-bar
            app
            clipped-right
            flat
            height="72"
        >
            <h2>Channel name</h2>
            <v-spacer></v-spacer>

            <v-responsive max-width="156">
                <v-text-field
                    dense
                    flat
                    hide-details
                    rounded
                    solo-inverted
                ></v-text-field>
            </v-responsive>
        </v-app-bar>

        <v-navigation-drawer
            v-model="drawer"
            app
            width="300"
        >
            <v-sheet
                color="grey lighten-5"
                height="128"
                width="100%"
                class="pr-2"
            >
                <CurrentUserPanel></CurrentUserPanel>
            </v-sheet>

            <v-list
                class="pl-4 mt-4 d-flex flex-column justify-space-between"
                shaped
            >
                <div>
                    <v-list-item
                        v-for="n in 5"
                        :key="n"
                        link
                    >
                        <v-list-item-content>
                            <v-list-item-title><b>#</b> Item {{ n }}</v-list-item-title>
                        </v-list-item-content>
                    </v-list-item>
                </div>
            </v-list>

        </v-navigation-drawer>

        <v-navigation-drawer
            app
            clipped
            right
        >
            <v-list>
                <v-list-item
                    v-for="n in 5"
                    :key="n"
                    link
                >
                    <v-list-item-content>
                        <v-list-item-title>Item {{ n }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>

            </v-list>
        </v-navigation-drawer>

        <v-main>
            <router-view></router-view>
        </v-main>

        <v-footer
            app
            color="transparent"
            height="72"
            inset
        >
            <v-text-field
                background-color="grey lighten-1"
                dense
                flat
                hide-details
                rounded
                solo
            ></v-text-field>
        </v-footer>
    </v-app>

</template>

<script>
import CurrentUserPanel from "./components/CurrentUserPanel";

export default {
    data: () => ({ drawer: null }),
    components: {
        CurrentUserPanel
    }
}
</script>

<style scoped>

</style>
