<template>
  <div
    :class="[
      'rounded-lg backdrop-blur-sm border p-3 transition-all duration-200',
      boxClasses.bg,
      boxClasses.border,
      boxClasses.hoverBg,
      boxClasses.hoverBorder
    ]"
  >
    <div class="text-center">
      <!-- Title -->
      <p :class="['text-xs font-medium uppercase tracking-wide mb-1', boxClasses.title]">
        {{ title }}
      </p>

      <!-- Value -->
      <p :class="['text-lg font-bold mb-2', boxClasses.value]">
        {{ value }}
      </p>

      <!-- Growth -->
       <div v-if="growth != null" class="inline-flex items-center space-x-1"> 
            <div :class="['inline-flex items-center rounded-md px-2 py-1', growthClasses]">
                <component :is="growthIcon" class="h-3 w-3 mr-1" />
                <span class="text-xs font-medium">{{ growth }}%</span>
            </div>

            <!-- Comparison text -->
            <InformationCircleIcon
                v-if="comparisonText"
                @click.stop="showDetails" 
                class="w-4 h-4 text-baseColor-300 hover:text-brandColor-800 cursor-pointer transition"
            /> 
        </div>
        <!-- <Teleport to="body"> 
            <Tooltip
                v-if="showPopup"
                :position="tooltipPosition"
                @close="showPopup = false"
            >
                <p class="text-sm text-baseColor-800">{{ comparisonText }}</p>
            </Tooltip>
        </Teleport> -->
    </div>
  </div>
</template>

<script setup>
    import { computed, ref } from 'vue'
    import { ArrowUpIcon, ArrowDownIcon, InformationCircleIcon} from '@heroicons/vue/24/outline';
   

    const props = defineProps({
    title: String,
    value: [Number, String],
    growth: { type: Number, default: null },
    comparisonText: String,
    boxType: { type: String, default: 'default' }
    })

    const growthIcon = computed(() => { 
        if (props.growth < 0) return ArrowDownIcon
        if (props.growth === 0) return null
        return ArrowUpIcon
    })
    const growthClasses = computed(() => {
        if (props.growth < 0) return 'bg-error-400 text-error-600'
        if (props.growth === 0) return 'bg-yellow-400 text-yellow-700'
        return 'bg-green-400 text-green-800'
    })
    // variable and function for description
    const showPopup = ref(false)
    const tooltipPosition = ref({ top: 0, left: 0 })
    const showDetails = (event) => {
        const rect = event.target.getBoundingClientRect()
        tooltipPosition.value = {
            top: rect.bottom + window.scrollY + 8, // 8px offset below icon
            left: rect.left + window.scrollX - 14
        }
        showPopup.value = !showPopup.value
    }
    const boxStyles = {
        default: {
            bg: 'bg-brandColor-800/15',
            border: 'border-brandColor-500/25',
            hoverBg: 'hover:bg-baseColor-800/70',
            hoverBorder: 'hover:border-brandColor-500/40',
            title: 'text-brandColor-400',
            value: 'text-brandColor-50',
            comparisonText: "text-baseColor-300"
        },
        dashboardLight: {
            bg: 'bg-bg-brandColor-600/15 backdrop-blur-sm',
            border: 'border-brandColor-500/25',
            hoverBg: 'hover:bg-brandColor-600/70',
            hoverBorder: 'hover:border-brandColor-500/40',
            title: 'text-brandColor-300',
            value: 'text-brandColor-50',
            comparisonText: "text-baseColor-200"
        },
        dashboardDark: {
            bg: 'bg-brandColor-800/15 backdrop-blur-sm',
            border: 'border-brandColor-500/25',
            hoverBg: 'hover:bg-brandColor-800/15',
            hoverBorder: 'hover:border-brandColor-500/40',
            title: 'text-brandColor-300',
            value: 'text-brandColor-50',
            comparisonText: "text-baseColor-300"
        },
        customers: {
            bg: 'bg-blue-800/15',
            border: 'border-blue-500/25',
            hoverBg: 'hover:bg-blue-800/70',
            hoverBorder: 'hover:border-blue-500/40',
            title: 'text-blue-400',
            value: 'text-blue-50',
            comparisonText: "text-baseColor-300"
        },
    } 

    const boxClasses = computed(() => {
     return boxStyles[props.boxType] || boxStyles.default
    })
</script>
