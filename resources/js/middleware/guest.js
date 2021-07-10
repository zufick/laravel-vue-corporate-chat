export default function guest({ to, next, store }) {
    const dashboardQuery = { path: "/"};

    if (store.user.id) {
        next(dashboardQuery);
    } else {
        next();
    }
}
