<script>
    import { Link, page } from "@inertiajs/svelte";
    import GoodpayLogo from '../../img/goodpay.png'
    import NavLink from "../Components/NavLink.svelte";
    import Dropdown from "../Components/Dropdown.svelte";
    import DropdownLink from "../Components/DropdownLink.svelte";
    import ResponsiveNavLink from "../Components/ResponsiveNavLink.svelte";
    import {onMount} from "svelte";
    import axios from "axios";

    let showingNavigationDropdown = false;
    let authUserRoles;
    let isAdmin, isEmployee;

    onMount(async () => {
        axios.get('/auth-user-roles').then(response => {
            authUserRoles = response.data;
            setRoles();
        });
    });

    function setRoles() {
        isEmployee = authUserRoles.some(role => role.name == 'Cashier');
        isAdmin = authUserRoles.some(role => role.name === 'Administrator');
    }
</script>

<div class="auth-layout">
    <div class="flex flex-col min-h-screen bg-white">
        <nav
            class="bg-white border-b-2 border-blue-600"
        >
            <div class="mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col lg:flex-row justify-between items-center py-2">
                    <img class="flex h-12" src="{GoodpayLogo}">
                    <div class="flex">
                        <div
                            class="lg:space-x-4 xl:space-x-8 mt-4 lg:mt-0 space-y-2 lg:space-y-0 sm:-my-px lg:ml-10 flex flex-col lg:flex-row"
                        >
                            <NavLink
                                href={route("dashboard")}
                                active={route().current("dashboard")}
                            >
                                Dashboard
                            </NavLink>

                            {#if isEmployee }
                                <NavLink
                                    href={route("pos.index")}
                                    active={route().current("pos.index")}
                                >
                                    Kassa
                                </NavLink>

                                <NavLink
                                    href={route("help-requests.index")}
                                    active={route().current("help-requests.index")}
                                >
                                    Hulpaanvragen
                                </NavLink>

                                <NavLink
                                    href={route("admin.dishes.menu")}
                                    active={route().current("admin.dishes.menu")}
                                >
                                    Menu
                                </NavLink>

                                <NavLink
                                    href={route("table-registration.cashier-index")}
                                    active={route().current("table-registration.cashier-index")}
                                >
                                    Tafelbestellingen
                                </NavLink>
                            {/if}

                            {#if isAdmin}
                                <NavLink
                                    href={route("admin.sales.index")}
                                    active={route().current("admin.sales.index")}
                                >
                                    Verkoopoverzicht
                                </NavLink>

                                <NavLink
                                    href={route("news.index")}
                                    active={route().current("news.index")}
                                >
                                    Nieuws
                                </NavLink>

                                <NavLink
                                    href={route("admin.planning.index")}
                                    active={route().current("admin.planning.index")}
                                >
                                    Tafelplanning
                                </NavLink>

                                <NavLink
                                    href={route("admin.dishes.index")}
                                    active={route().current("admin.dishes.index")}
                                >
                                    Gerechtbeheer
                                </NavLink>

                                <NavLink
                                    href={route("admin.reviews.index")}
                                    active={route().current("admin.reviews.index")}
                                >
                                    Reviews
                                </NavLink>
                            {/if}

                            <div class="flex sm:items-center lg:ml-6">
                                <div class="ml-3 relative inline-flex h-fit items-center px-8 py-0 rounded-lg border border-blue-600 text-blue-600 font-bold bg-blue-100">
                                    <Dropdown align="right" width="48">
                                        <svelte:fragment slot="trigger">
                                    <span class="inline-flex rounded-md">
                                        <button
                                            type="button"
                                            class="inline-flex items-center bg-blue-100 text-blue-600 font-bold border border-transparent text-sm leading-4 font-medium rounded-md hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                        >
                                            {$page.props.auth.user.name}

                                            <svg
                                                class="ml-2 -mr-0.5 h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </button>
                                    </span>
                                        </svelte:fragment>

                                        <svelte:fragment slot="content">
                                            <!-- content here -->
                                            <DropdownLink href={route("profile.edit")}>
                                                Profile
                                            </DropdownLink>
                                            <DropdownLink
                                                href={route("logout")}
                                                method="post"
                                                as="button"
                                            >
                                                Log Out
                                            </DropdownLink>
                                        </svelte:fragment>
                                    </Dropdown>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div></div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="flex-1">
            <slot />
        </main>
    </div>
</div>

