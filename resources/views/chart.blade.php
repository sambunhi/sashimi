<!DOCTYPE html>
<html data-scheme="light">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>sambunhi</title>
    <meta property="og:site_name" content="sambunhi" />
    <meta property="og:title" content="網路聲量變化" />
    <meta property="og:description" content="TSMC 期末專案" />
    <meta property="og:image" content="https://sambunhi.nycu.one/assets/cover.jpg" />
    <link rel="stylesheet" href="https://sambunhi.nycu.one/assets/tocas.css" />
    <style>
      body {
        font-family: sans-serif;
      }
    </style>
  </head>
  <body>
    <div class="ts-content is-vertically-padded">
      <div class="ts-container">
        <div class="ts-row is-middle-aligned">
          <div class="column is-fluid is-center-aligned">
            <div class="ts-header is-huge is-heavy">🍣 Sambunhi</div>
          </div>
        </div>
        <div class="ts-divider is-section"></div>

        <div class="ts-tab is-pilled is-fluid">
          <a class="item is-active" id="duration-3d" onclick="updateChart(3);"> 最近 3 天 </a>
          <a class="item" id="duration-7d" onclick="updateChart(7);"> 最近 7 天 </a>
          <a class="item" id="duration-14d" onclick="updateChart(14);"> 最近 14 天 </a>
          <a class="item" id="duration-30d" onclick="updateChart(30);"> 最近 30 天 </a>
        </div>
        <div class="ts-header is-huge mt-4 mb-4">網路聲量變化</div>
        <div id="chart" style="height: 400px; text-align: center"></div>
        <div id="results">Please click the above chart.</div>
        <template id="tmpl">
          <div class="ts-segment">
            <p id="title">無標題新聞</p>
            <span id="source" class="ts-badge is-negative">來源</span>
            <span id="date">2042-12-01</span>
            <br />
          </div>
        </template>
      </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/tocas/4.0.4/tocas.min.js"></script>
    <script src="https://d3js.org/d3.v7.min.js"></script>

    <script>
      // Copyright 2021 Observable, Inc.
      // Released under the ISC license.
      // https://observablehq.com/@d3/multi-line-chart
      // Modified: Sean 2022-06-06
      function LineChart(
        data,
        {
          x, // given d in data, returns the (temporal) x-value
          y, // given d in data, returns the (quantitative) y-value
          z, // given d in data, returns the (categorical) z-value
          yLabel, // a label for the y-axis
          width, // outer width, in pixels
          height, // outer height, in pixels
          color, // stroke color of line
          voronoi, // show a Voronoi overlay? (for debugging)
        } = {}
      ) {
        // Default variables
        marginTop = 20; // top margin, in pixels
        marginBottom = 40; // bottom margin, in pixels
        marginLeft = 40; // left margin, in pixels
        marginRight = 30; // right margin, in pixels
        xRange = [marginLeft, width - marginRight]; // [left, right]
        yRange = [height - marginBottom, marginTop]; // [bottom, top]
        mixBlendMode = "multiply"; // blend mode of lines

        // Compute values.
        const defined = (d, i) => !isNaN(X[i]) && !isNaN(Y[i]);
        const X = d3.map(data, x);
        const Y = d3.map(data, y);
        const Z = d3.map(data, z);
        const O = d3.map(data, (d) => d);
        const D = d3.map(data, defined);
        const C = {}; // keyword -> color mapping
        const T = Z; // Compute titles.

        // Compute default domains, and unique the z-domain.
        const xDomain = d3.extent(X);
        const yDomain = [0, d3.max(Y, (d) => (typeof d === "string" ? +d : d))];
        const zDomain = new d3.InternSet(Z);
        const I = d3.range(X.length).filter((i) => zDomain.has(Z[i])); // Omit any data not present in the z-domain.

        const duration = (xDomain[1].getTime() - xDomain[0].getTime()) / 86400_000;

        // Construct scales and axes.
        const xScale = d3.scaleUtc(xDomain, xRange);
        const yScale = d3.scaleLinear(yDomain, yRange);
        const xAxis = d3
          .axisBottom(xScale)
          .ticks(Math.min(width / 80, duration))
          .tickSizeOuter(0)
          .tickFormat((d, i) => d.toISOString().substr(0, 10));
        const yAxis = d3.axisLeft(yScale).ticks(height / 60);

        // Construct a line generator.
        const line = d3
          .line()
          .defined((i) => D[i])
          .curve(d3.curveLinear)
          .x((i) => xScale(X[i]))
          .y((i) => yScale(Y[i]));

        // Base canvas
        const chart = d3.select("#chart").html("");
        const svg = chart
          .append("svg")
          .attr("width", width)
          .attr("height", height)
          .attr("viewBox", [0, 0, width, height])
          .attr("style", "max-width: 100%; height: auto; height: intrinsic;")
          .style("-webkit-tap-highlight-color", "transparent")
          .on("click", click)
          .on("pointerenter", pointerentered)
          .on("pointermove", pointermoved)
          .on("pointerleave", pointerleft)
          .on("touchstart", (event) => event.preventDefault());

        // An optional Voronoi display (for fun).
        if (voronoi)
          svg
            .append("path")
            .attr("fill", "none")
            .attr("stroke", "#ccc")
            .attr(
              "d",
              d3.Delaunay.from(
                I,
                (i) => xScale(X[i]),
                (i) => yScale(Y[i])
              )
                .voronoi([0, 0, width, height])
                .render()
            );

        // X-Axis
        svg
          .append("g")
          .attr("transform", `translate(0, ${height - marginBottom})`)
          .call(xAxis);

        // Y-Axis
        svg
          .append("g")
          .attr("transform", `translate(${marginLeft}, 0)`)
          .call(yAxis)
          .call((g) => g.select(".domain").remove());

        // Voronoi
        svg.call(
          voronoi
            ? () => {}
            : (g) =>
                g
                  .selectAll(".tick line")
                  .clone()
                  .attr("x2", width - marginLeft - marginRight)
                  .attr("stroke-opacity", 0.1)
        );

        // Data lines
        const path = svg
          .append("g")
          .attr("fill", "none")
          .attr("stroke-width", 1.5)
          .selectAll("path")
          .data(d3.group(I, (i) => Z[i]))
          .join("path")
          .style("mix-blend-mode", mixBlendMode)
          .attr("stroke", ([z], i) => {
            C[z] = color[i];
            return color[i];
          })
          .attr("d", ([, I]) => line(I));

        // Data dots
        const dot = svg.append("g").attr("display", "none");
        dot.append("circle").attr("r", 2.5);

        dot
          .append("text")
          .attr("font-family", "sans-serif")
          .attr("font-size", 10)
          .attr("text-anchor", "middle")
          .attr("y", -8);

        // Legend
        let legendY = 80;
        for (name in C) {
          svg.append("circle").attr("cx", 50).attr("cy", legendY).attr("r", 6).style("fill", C[name]);
          svg
            .append("text")
            .attr("x", 65)
            .attr("y", legendY)
            .attr("dominant-baseline", "middle")
            .text(name)
            .style("font-size", "16px");
          legendY += 30;
        }

        function click(event) {
          const [xm, ym] = d3.pointer(event);
          const i = d3.least(I, (i) => Math.hypot(xScale(X[i]) - xm, yScale(Y[i]) - ym)); // closest point

          keyword = "";
          if (ym < height - marginBottom) keyword = Z[i];
          date = X[i].toISOString().substr(0, 10);

          fetch("https://sambunhi.nycu.one/api/v1/articles" + `?date=${date}&keyword=${keyword}`)
            .then((r) => r.json())
            .then((articles) => {
              console.log(articles);

              const results = document.getElementById("results");
              const tmpl = document.getElementById("tmpl");
              results.innerHTML = "";
              for (let item of articles) {
                const card = document.createElement("div");
                card.append(tmpl.content.cloneNode(true));

                const titleElem = card.querySelector("#title");
                const sourceElem = card.querySelector("#source");
                const dateElem = card.querySelector("#date");

                titleElem.innerText = item.title;
                sourceElem.innerText = item.source.name;
                for (let trend of item.trend) {
                  let elem = document.createElement("span");
                  elem.classList = "ts-badge";
                  elem.innerText = trend.keyword;
                  elem.style.backgroundColor = C[trend.keyword];
                  dateElem.parentNode.insertBefore(elem, dateElem);
                }
                dateElem.innerText = item.published_at;
                results.append(card);
              }
            });
        }

        const insideLegend = false;
        function pointermoved(event) {
          const [xm, ym] = d3.pointer(event);
          const i = d3.least(I, (i) => Math.hypot(xScale(X[i]) - xm, yScale(Y[i]) - ym)); // closest point

          path
            .style("stroke", ([z]) => (Z[i] === z ? null : "#ddd"))
            .filter(([z]) => Z[i] === z)
            .raise();

          dot
            .attr("transform", `translate(${xScale(X[i])}, ${yScale(Y[i])})`)
            .select("text")
            .text(T[i]);
          svg.property("value", O[i]).dispatch("input", {
            bubbles: true,
          });
        }

        function pointerentered() {
          path.style("mix-blend-mode", null).style("stroke", "#ddd");
          dot.attr("display", null);
        }

        function pointerleft() {
          path.style("mix-blend-mode", "multiply").style("stroke", null);
          dot.attr("display", "none");
          svg.node().value = null;
          svg.dispatch("input", { bubbles: true });
        }

        return Object.assign(svg.node(), { value: null });
      }
    </script>

    <script>
      window.addEventListener("load", (event) => {
        updateChart(3);
      });

      function updateChart(durationDays) {
        const btn3d = document.getElementById("duration-3d");
        const btn7d = document.getElementById("duration-7d");
        const btn14d = document.getElementById("duration-14d");
        const btn30d = document.getElementById("duration-30d");

        btn3d.classList.remove("is-active");
        btn7d.classList.remove("is-active");
        btn14d.classList.remove("is-active");
        btn30d.classList.remove("is-active");

        if (durationDays == 3) btn3d.classList.add("is-active");
        if (durationDays == 7) btn7d.classList.add("is-active");
        if (durationDays == 14) btn14d.classList.add("is-active");
        if (durationDays == 30) btn30d.classList.add("is-active");

        const endTs = new Date();
        const beginTs = endTs - 86400_000 * (durationDays - 1);
        let endDate = new Date(endTs).toISOString().substr(0, 10);
        let beginDate = new Date(beginTs).toISOString().substr(0, 10);

        fetch(`https://sambunhi.nycu.one/api/v1/trends?date_start=${beginDate}&date_end=${endDate}`)
          .then((r) => r.json())
          .then((r) => {
            let beginDate = "2099-00-00";
            let endDate = "1000-00-00";
            let trends = [];
            let cnt = {};
            for (let k of r.trends) {
              if (beginDate > k.date) beginDate = k.date;
              if (endDate < k.date) endDate = k.date;
              if (undefined === cnt[k.keyword]) cnt[k.keyword] = new Map();
              cnt[k.keyword].set(k.date, k.cnt);
            }

            const beginTs = new Date(beginDate).getTime();
            const endTs = new Date(endDate).getTime();
            for (let ts = beginTs; ts <= endTs; ts += 86400_000) {
              let curDate = new Date(ts).toISOString().substr(0, 10);
              for (k in cnt) {
                trends.push({
                  date: curDate,
                  cnt: (cnt[k].get(curDate) || 0) + Math.random() * 0.5 - 0.1,
                  keyword: k,
                });
              }
            }
            return trends;
          })
          .then((trends) => {
            renderChart(trends);
          });
      }

      function renderChart(trends) {
        console.log(trends);
        const chart = LineChart(trends, {
          x: (d) => new Date(d.date),
          y: (d) => d.cnt,
          z: (d) => d.keyword,
          yLabel: "網路聲量變化",
          width: 640,
          height: 400,
          color: ["#00A2FF", "#61D836", "#D31976", "#F8BA00", "#9814D6", "#892319", "#6C6F39", "#4D2501"],
          voronoi: false,
        });
      }
    </script>
  </body>
</html>
